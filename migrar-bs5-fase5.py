#!/usr/bin/env python3
"""
Fase 5 de migracion-bootstrap5.md: modulos de F:\\invoiceflash\\modulos.

Aplica a los .tpl, .php y .xml de los modulos las mismas reglas de las fases 1-3 (que se cargan de sus scripts),
con dos diferencias:
  - en los XML de vqmod NO se toca nada dentro de <search>...</search> (es texto que debe casar con el nucleo);
    solo lo que va en <add>;
  - tooltip: si la etiqueta ya trae data-bs-toggle="tooltip" ademas de data-toggle="tooltip", se quita el viejo.

Simula por defecto; con --aplicar escribe.

  python migrar-bs5-fase5.py                       simula todo
  python migrar-bs5-fase5.py --modulo compras --detalle
  python migrar-bs5-fase5.py --regla grupos --aplicar
  python migrar-bs5-fase5.py --modulo compras --aplicar
"""
import argparse
import importlib.util
import io
import os
import re
import sys

RAIZ = os.path.dirname(os.path.abspath(__file__))
MODULOS = os.path.join(os.path.dirname(RAIZ), 'modulos')
IGNORADOS = {'modulo_web', 'web2', 'web3', 'web_vweb', 'webs_clientes_viejas_datos', '.git', '.claude',
             'crear_demos_automatizadas', 'stripe'}


def cargar(nombre, fichero):
    spec = importlib.util.spec_from_file_location(nombre, os.path.join(RAIZ, fichero))
    mod = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(mod)
    return mod


f3 = cargar('fase3', 'migrar-bs5-fase3.py')
f2 = f3.f2
f1 = f2.f1

ORDEN = (['texto', 'float', 'errores', 'label', 'close', 'tooltip'] +
         ['panel', 'espacios', 'peso', 'badge', 'well', 'bloque'] +
         ['ocultar', 'sinefecto', 'ayuda', 'grupos', 'formularios'])
F1 = {'texto', 'float', 'errores', 'label', 'close', 'tooltip'}
F2 = {'panel', 'espacios', 'peso', 'badge', 'well', 'bloque'}

RE_SEARCH = re.compile(r'<search\b.*?</search>', re.S | re.I)
RE_TAG_TOOLTIP = re.compile(r'<[a-zA-Z][^<>]*?data-toggle=\\?["\']tooltip[^<>]*>', re.S)


def aplicar_regla(regla, texto, avisos, nombre):
    if regla in F1:
        if regla == 'tooltip':
            def sub(m):
                t = m.group(0)
                if re.search(r'data-bs-toggle=\\?["\']tooltip', t):
                    f1.CUENTA[0] += 1
                    return re.sub(r'\s+data-toggle=(\\?)(["\'])tooltip\1?\2', '', t, count=1)
                return t
            texto = RE_TAG_TOOLTIP.sub(sub, texto)
        return f1.aplicar_regla(regla, texto, avisos, nombre)
    if regla in F2 or regla == 'ocultar' and False:
        return f2.aplicar(regla, texto, avisos, nombre)
    return f3.aplicar(regla, texto, avisos, nombre)


def con_xml(regla, texto, avisos, nombre, es_xml):
    if not es_xml:
        return aplicar_regla(regla, texto, avisos, nombre)
    # se enmascaran los <search>
    guardados = []

    def g(m):
        guardados.append(m.group(0))
        return '\x03%d\x03' % (len(guardados) - 1)
    enm = RE_SEARCH.sub(g, texto)
    enm = aplicar_regla(regla, enm, avisos, nombre)
    return re.sub('\x03(\\d+)\x03', lambda m: guardados[int(m.group(1))], enm)


def ficheros(modulos):
    for m in sorted(os.listdir(MODULOS)):
        if m in IGNORADOS or (modulos and m not in modulos):
            continue
        base = os.path.join(MODULOS, m)
        if os.path.isfile(base):
            if base.lower().endswith('.xml'):
                yield base
            continue
        for d, dirs, files in os.walk(base):
            dirs[:] = [x for x in dirs if x not in IGNORADOS]
            for f in sorted(files):
                if f.lower().endswith(('.tpl', '.php', '.xml')):
                    yield os.path.join(d, f)
    tb = os.path.join(os.path.dirname(MODULOS), 'TicketBai')   # repo propio, hermano de modulos
    if os.path.isdir(tb) and (not modulos or 'TicketBai' in modulos):
        for d, dirs, files in os.walk(tb):
            dirs[:] = [x for x in dirs if x not in IGNORADOS]
            for f in sorted(files):
                if f.lower().endswith(('.tpl', '.php', '.xml')):
                    yield os.path.join(d, f)


def main():
    ap = argparse.ArgumentParser(description='Fase 5 de la migracion a Bootstrap 5 (modulos).')
    ap.add_argument('--regla', choices=ORDEN)
    ap.add_argument('--modulo', action='append')
    ap.add_argument('--fichero', action='append', help='solo rutas que contengan el texto')
    ap.add_argument('--aplicar', action='store_true')
    ap.add_argument('--detalle', action='store_true')
    args = ap.parse_args()
    reglas = [args.regla] if args.regla else ORDEN
    avisos = []
    total = {r: [0, 0] for r in reglas}
    cambiados = set()
    for ruta in ficheros(args.modulo):
        rel = os.path.relpath(ruta, MODULOS).replace('\\', '/')
        if args.fichero and not any(a in rel for a in args.fichero):
            continue
        with io.open(ruta, encoding='utf-8', errors='surrogateescape', newline='') as fh:
            original = fh.read()
        texto = original
        es_xml = ruta.lower().endswith('.xml')
        for r in reglas:
            f1.CUENTA[0] = 0
            nuevo = con_xml(r, texto, avisos, rel, es_xml)
            if nuevo != texto:
                total[r][0] += f1.CUENTA[0]
                total[r][1] += 1
                if args.detalle:
                    import difflib
                    for linea in difflib.unified_diff(texto.splitlines(), nuevo.splitlines(), rel, rel, n=0, lineterm=''):
                        if linea[:1] in '+-' and linea[:3] not in ('+++', '---'):
                            print(('  ' + linea.strip()[:170]).encode('ascii', 'replace').decode('ascii'))
                texto = nuevo
        if texto != original:
            cambiados.add(rel)
            if args.aplicar:
                with io.open(ruta, 'w', encoding='utf-8', errors='surrogateescape', newline='') as fh:
                    fh.write(texto)
    print('%s%s' % ('APLICADO' if args.aplicar else 'SIMULACION (no se ha escrito nada)',
                    ' | modulos: ' + ', '.join(args.modulo) if args.modulo else ''))
    for r in reglas:
        print('  %-12s %5d cambios en %3d ficheros' % (r, total[r][0], total[r][1]))
    print('  %d ficheros cambian en total' % len(cambiados))
    for a in avisos:
        print('  AVISO %s: %s' % a)


if __name__ == '__main__':
    if hasattr(sys.stdout, 'reconfigure'):
        sys.stdout.reconfigure(encoding='utf-8')
    main()
