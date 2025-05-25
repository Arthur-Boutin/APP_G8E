<?php

session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftySquirrel - Accueil</title>
    <link rel="stylesheet" href="style.css">
    <style>

        body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f6deb7}
        .chat-container{width:1400px;margin:40px auto;display:flex;gap:32px}
        .chat-sidebar{width:240px;background:#f0d9b3;padding:18px 14px;border-radius:10px}
        .conversation-item{background:#fff;border-radius:6px;padding:12px 14px;margin-bottom:12px;cursor:pointer;border:2px solid transparent;transition:.15s}
        .conversation-item:hover{border-color:#8b5a2b}
        .conversation-item.selected{border-color:#8b5a2b;background:#fdfbf7}
        .conversation-name{font-weight:700;color:#3f2a14;margin:0}
        .conversation-preview{margin:4px 0 0;font-size:14px;color:#757575}
        .chat-main{flex:1;background:#fdfbf7;border-radius:10px;display:flex;flex-direction:column}
        .chat-header{background:#f0d9b3;padding:14px 20px;border-top-left-radius:10px;border-top-right-radius:10px;font-weight:700;color:#3f2a14;font-size:18px}
        .chat-messages{flex:1;padding:26px 30px;overflow-y:auto}
        .message{max-width:60%;padding:8px 12px;border-radius:8px;margin-bottom:16px;font-size:15px}
        .message.left{background:#dceffc;color:#1d3a5f;border-top-left-radius:0}
        .message.right{background:#c7f5c9;color:#1c3b1d;margin-left:auto;border-top-right-radius:0}
        .chat-input{display:flex;gap:12px;align-items:flex-end;background:#f0d9b3;padding:18px 20px;border-bottom-left-radius:10px;border-bottom-right-radius:10px}
        .chat-input textarea{flex:1;height:60px;resize:none;padding:10px 12px;font-size:15px;border:1px solid #ccc;border-radius:6px}
        .btn-send{background:#8b5a2b;color:#fff;border:none;border-radius:6px;padding:10px 24px;cursor:pointer;font-size:15px}
    </style>
</head>
<body>


<?php include 'header.php'; ?>

<div class="chat-container">

    <aside class="chat-sidebar">
        <h2>Conversations</h2>

        <div class="conversation-item selected" data-name="Artisan1">
            <p class="conversation-name">Artisan1</p>
            <p class="conversation-preview">Salut Jean !</p>
        </div>

        <div class="conversation-item" data-name="Artisan2">
            <p class="conversation-name">Artisan2</p>
            <p class="conversation-preview">…</p>
        </div>
    </aside>


    <section class="chat-main">
        <div class="chat-header" id="chatTitle">Artisan1</div>
        <div class="chat-messages" id="chatBox"></div>
        <div class="chat-input">
            <textarea id="msg" placeholder="Écrivez un message…"></textarea>
            <button id="send" class="btn-send">Envoyer</button>
        </div>
    </section>
</div>

<script>

    const data = {
        "Artisan1":[
            {side:"left",  text:"Salut Marie !"},
            {side:"right", text:"Salut Jean !"}
        ],
        "Artisan2":[
            {side:"left",  text:"Bonjour !"}
        ]
    };


    const items = document.querySelectorAll('.conversation-item');
    const title = document.getElementById('chatTitle');
    const box   = document.getElementById('chatBox');
    const ta    = document.getElementById('msg');
    const btn   = document.getElementById('send');


    function addMsg(side, text){
        const d=document.createElement('div');
        d.className='message '+side;
        d.innerHTML='<p>'+text+'</p>';
        box.appendChild(d);
    }

    function setPreview(name, txt){
        items.forEach(i=>{
            if(i.dataset.name===name){
                i.querySelector('.conversation-preview').textContent = txt;
            }
        });
    }


    function displayConversation(name){
        title.textContent = name;
        box.innerHTML = '';
        (data[name]||[]).forEach(m=>addMsg(m.side,m.text));
        box.scrollTop = box.scrollHeight;
        if(data[name]?.length){
            setPreview(name, data[name][data[name].length-1].text);
        }
    }


    items.forEach(item=>{
        item.onclick=()=>{
            document.querySelector('.conversation-item.selected')?.classList.remove('selected');
            item.classList.add('selected');
            displayConversation(item.dataset.name);
        };
    });


    btn.onclick = ()=>{
        const txt = ta.value.trim();
        if(!txt) return;
        const name = title.textContent;
        data[name] = data[name]||[];
        data[name].push({side:'right', text:txt});
        ta.value='';
        displayConversation(name);
        setPreview(name, txt);
    };

    ta.onkeydown = e=>{
        if(e.key==='Enter'&&!e.shiftKey){ e.preventDefault(); btn.onclick(); }
    };


    displayConversation('Artisan1');
</script>
<?php include 'footer.php'; ?>
</body>
</html>
