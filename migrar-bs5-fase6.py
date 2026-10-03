#!/usr/bin/env python3
"""
Fase 6 de migracion-bootstrap5.md: ajustes de marcado necesarios al sustituir el CSS por Bootstrap 5.

  selects   <select class="form-control[-sm|-lg]">  ->  <select class="form-select[-sm|-lg]">
            (en Bootstrap 5 .form-control quita la flecha del desplegable nativo)

Ambito: admin/ del nucleo (.tpl y .php) y los modulos + TicketBai (.tpl, .php, .xml; en los XML no se toca <search>).
Simula por defecto; con --aplicar escribe.
"""
import argparse
import importlib.util
import io
import os
import re
import sys

RAIZ = os.path.dirname(os.path.abspath(__file__))
spec = importlib.util.spec_from_file_location('fase5', os.path.join(RAIZ, 'migrar-bs5-fase5.py'))
f5 = importlib.util.module_from_spec(spec)
spec.loader.exec_module(f5)

RE_SELECT = re.compile(r'<select\b(?:[^<>]|<\?.*?\?>)*?>', re.I | re.S)
CUENTA = [0]


def selects(texto):
    def sub_tag(m):
        tag = m.group(0)

        def sub_class(mc):
            val = mc.group(3)
            nuevo = re.sub(r'(?<![\w-])form-control(-sm|-lg)?(?![\w-])', lambda x: 'form-select' + (x.group(1) or ''), val)
            if nuevo != val:
                CUENTA[0] += 1
            return mc.group(1) + mc.group(2) + nuevo + mc.group(2)
        return re.sub(r'(class\s*=\s*)(\\?["\'])([^"\']*)\2', lambda mc: sub_class(mc), tag, flags=re.I)
    return RE_SELECT.sub(sub_tag, texto)


def ficheros():
    base = os.path.join(RAIZ, 'admin')
    for d, dirs, files in os.walk(base):
        dirs[:] = [x for x in dirs if x not in ('javascript', 'cache', 'logs')]
        for f in sorted(files):
            if f.lower().endswith(('.tpl', '.php')):
                yield os.path.join(d, f)
    yield os.path.join(RAIZ, 'admin', 'view', 'javascript', 'common.js')
    for r in f5.ficheros(None):
        yield r


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument('--aplicar', action='store_true')
    args = ap.parse_args()
    total = 0
    cambiados = []
    for ruta in ficheros():
        with io.open(ruta, encoding='utf-8', errors='surrogateescape', newline='') as fh:
            original = fh.read()
        CUENTA[0] = 0
        es_xml = ruta.lower().endswith('.xml')
        if es_xml:
            guardados = []

            def g(m):
                guardados.append(m.group(0))
                return '\x03%d\x03' % (len(guardados) - 1)
            enm = f5.RE_SEARCH.sub(g, original)
            enm = selects(enm)
            nuevo = re.sub('\x03(\\d+)\x03', lambda m: guardados[int(m.group(1))], enm)
        else:
            nuevo = selects(original)
        if nuevo != original:
            total += CUENTA[0]
            cambiados.append(ruta)
            if args.aplicar:
                with io.open(ruta, 'w', encoding='utf-8', errors='surrogateescape', newline='') as fh:
                    fh.write(nuevo)
    print('%s: %d selects en %d ficheros' % ('APLICADO' if args.aplicar else 'SIMULACION', total, len(cambiados)))


if __name__ == '__main__':
    if hasattr(sys.stdout, 'reconfigure'):
        sys.stdout.reconfigure(encoding='utf-8')
    main()
