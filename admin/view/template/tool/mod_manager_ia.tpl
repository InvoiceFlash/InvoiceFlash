<?php echo $header; ?>
<?php include(DIR_TEMPLATE . 'common/template-header.tpl'); ?>
<div class="panel panel-default">
	<div class="panel-heading clearfix">
		<div class="h2"><i class="fa fa-robot"></i> <?php echo $heading_title; ?></div>
		<div style="float:right;margin-top:-4px;">
			<a href="<?php echo $cancel; ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Volver</a>
		</div>
	</div>
	<div class="panel-body">

		<?php if (!$config_claude_api_key) { ?>
		<div class="alert alert-warning">No hay ninguna API key de Claude configurada. Ve a Ajustes &gt; IA para configurarla.</div>
		<?php } ?>

		<div class="row mb-3">
			<label class="col-sm-2 col-form-label" style="padding-top:6px;">Timeout del servidor web</label>
			<div class="col-sm-10" style="padding-top:6px;">
				<span class="text-muted">
					<?php if ($server_software) { ?><?php echo $server_software; ?> &mdash; <?php } ?><?php echo $server_timeout; ?>
				</span>
			</div>
		</div>

		<div id="chat-window">
			<div id="chat-messages">
				<div id="empty-msg" class="empty-hint">
					<i class="fa fa-robot fa-2x"></i>
					<p>Soy tu asistente IA para crear y modificar módulos VQMod.</p>
					<p class="text-muted">Solo puedo trabajar con archivos <code>.xml</code> dentro de la carpeta <code>vqmod/xml/</code>.</p>
				</div>
			</div>
			<div id="typing-indicator" style="display:none;">
				<div class="msg-ai"><div class="bubble typing"><i class="fa fa-circle-o-notch fa-spin"></i> Pensando...</div></div>
			</div>
		</div>

		<div class="input-group">
			<textarea id="chat-input" class="form-control" rows="2" placeholder="Describe el módulo que quieres crear o modificar..."></textarea>
			<span class="input-group-btn">
				<button class="btn btn-primary" id="send-btn" type="button">
					<i class="fa fa-paper-plane"></i><span class="hidden-xs"> Enviar</span>
				</button>
			</span>
		</div>
		<small class="text-muted">Ctrl+Enter para enviar &nbsp;·&nbsp; Solo puede crear/modificar archivos en <code>vqmod/xml/</code></small>

	</div>
</div>

<style>
#chat-window {
	height: 460px;
	overflow-y: auto;
	border: 1px solid #ddd;
	border-radius: 6px;
	padding: 14px;
	background: #f8f9fa;
	margin-bottom: 10px;
}
.empty-hint {
	text-align: center;
	padding: 40px 20px;
	color: #666;
}
.msg-user {
	text-align: right;
	margin-bottom: 12px;
}
.msg-user .bubble {
	display: inline-block;
	background: #0d6efd;
	color: #fff;
	padding: 8px 14px;
	border-radius: 18px 18px 4px 18px;
	max-width: 78%;
	text-align: left;
	word-wrap: break-word;
	white-space: pre-wrap;
}
.msg-ai {
	text-align: left;
	margin-bottom: 12px;
}
.msg-ai .bubble {
	display: inline-block;
	background: #fff;
	border: 1px solid #dee2e6;
	padding: 8px 14px;
	border-radius: 4px 18px 18px 18px;
	max-width: 85%;
	text-align: left;
	word-wrap: break-word;
}
.msg-ai .bubble pre {
	background: #f1f3f5;
	border: 1px solid #e0e0e0;
	padding: 8px 10px;
	border-radius: 4px;
	overflow-x: auto;
	margin: 8px 0 4px;
	font-size: 12px;
}
.msg-ai .bubble pre code {
	background: none;
	padding: 0;
}
.msg-ai .bubble code {
	background: #f1f3f5;
	padding: 1px 4px;
	border-radius: 3px;
	font-size: 12px;
}
.bubble.typing {
	color: #888;
	font-style: italic;
}
.msg-error .bubble {
	background: #fff3cd;
	border-color: #ffc107;
	color: #856404;
}
</style>

<script>
var chatUrl     = '<?php echo addslashes($chat_url); ?>';
var apiMessages = [];

document.getElementById('send-btn').addEventListener('click', sendMessage);
document.getElementById('chat-input').addEventListener('keydown', function(e) {
	if (e.ctrlKey && e.key === 'Enter') sendMessage();
});

function sendMessage() {
	var message = document.getElementById('chat-input').value.trim();

	if (!message) return;

	hideEmptyMsg();
	appendMessage('user', message);
	document.getElementById('chat-input').value = '';

	var typing = document.getElementById('typing-indicator');
	typing.style.display = 'block';
	document.getElementById('send-btn').disabled = true;
	scrollBottom();

	fetch(chatUrl, {
		method:  'POST',
		headers: { 'Content-Type': 'application/json' },
		body:    JSON.stringify({ messages: apiMessages, message: message })
	})
	.then(function(r) { return r.json(); })
	.then(function(data) {
		typing.style.display = 'none';
		document.getElementById('send-btn').disabled = false;
		if (data.error) {
			appendMessage('error', 'Error: ' + data.error);
		} else {
			apiMessages = data.messages;
			appendMessage('ai', data.reply);
		}
		scrollBottom();
	})
	.catch(function(err) {
		typing.style.display = 'none';
		document.getElementById('send-btn').disabled = false;
		appendMessage('error', 'Error de conexión: ' + err.message);
		scrollBottom();
	});
}

function appendMessage(role, text) {
	var container = document.getElementById('chat-messages');
	var div    = document.createElement('div');
	var bubble = document.createElement('div');
	bubble.className = 'bubble';

	if (role === 'user') {
		div.className    = 'msg-user';
		bubble.textContent = text;
	} else if (role === 'error') {
		div.className  = 'msg-ai msg-error';
		bubble.innerHTML = escHtml(text);
	} else {
		div.className    = 'msg-ai';
		bubble.innerHTML = renderMd(text);
	}

	div.appendChild(bubble);
	container.appendChild(div);
}

function hideEmptyMsg() {
	var el = document.getElementById('empty-msg');
	if (el) el.style.display = 'none';
}

function scrollBottom() {
	var win = document.getElementById('chat-window');
	win.scrollTop = win.scrollHeight;
}

function escHtml(s) {
	return String(s)
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;');
}

function renderMd(text) {
	text = escHtml(text);

	// Extract code blocks first
	var blocks = [];
	text = text.replace(/```([\s\S]*?)```/g, function(m, code) {
		var idx = blocks.length;
		blocks.push('<pre><code>' + code.replace(/^\n/, '') + '</code></pre>');
		return '\x00BLK' + idx + '\x00';
	});

	// Inline code
	text = text.replace(/`([^`\n]+?)`/g, '<code>$1</code>');
	// Bold
	text = text.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
	// Newlines → <br>
	text = text.replace(/\n/g, '<br>');

	// Restore code blocks
	text = text.replace(/\x00BLK(\d+)\x00/g, function(m, idx) {
		return blocks[parseInt(idx, 10)];
	});

	return text;
}
</script>
<?php echo $footer; ?>
