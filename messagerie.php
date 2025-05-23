<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Messagerie – Nutwork</title>
  <link rel="stylesheet" href="./style.css">

  <!-- correctifs & petite logique de messagerie ----------------------------->
  <style>
    /* --- mise en page générale --------------------------------------------- */
    .chat-container{max-width:1100px;margin:40px auto;display:flex;gap:32px}
    .chat-sidebar{width:240px;background:#f0d9b3;padding:18px 14px;border-radius:10px}
    .search-input{width:100%;padding:6px 8px;margin-bottom:14px;border:1px solid #ccc;border-radius:6px}
    .conversation-item{background:#fff;border-radius:6px;padding:12px 14px;margin-bottom:12px;
      cursor:pointer;border:2px solid transparent;transition:.15s}
    .conversation-item:hover{border-color:#8b5a2b}
    .conversation-item.selected{border-color:#8b5a2b;background:#fdfbf7}
    .conversation-name{font-weight:700;color:#3f2a14;margin:0}
    .conversation-preview{margin:4px 0 0;font-size:14px;color:#757575}

    .chat-main{flex:1;background:#fdfbf7;border-radius:10px;display:flex;flex-direction:column}
    .chat-header{background:#f0d9b3;padding:14px 20px;border-top-left-radius:10px;border-top-right-radius:10px;
      font-weight:700;color:#3f2a14;font-size:18px}
    .chat-messages{flex:1;padding:26px 30px;overflow-y:auto}
    .message{max-width:60%;padding:8px 12px;border-radius:8px;margin-bottom:16px;font-size:15px}
    .message.left {background:#dceffc;color:#1d3a5f;border-top-left-radius:0}
    .message.right{background:#c7f5c9;color:#1c3b1d;margin-left:auto;border-top-right-radius:0}

    /* --- zone de saisie ----------------------------------------------------- */
    .chat-input{display:flex;gap:12px;align-items:flex-end;background:#f0d9b3;
      padding:18px 20px;border-bottom-left-radius:10px;border-bottom-right-radius:10px}
    .chat-input textarea{flex:1;height:60px;resize:none;padding:10px 12px;font-size:15px;
      border:1px solid #ccc;border-radius:6px}
    .btn-send{background:#8b5a2b;color:#fff;border:none;border-radius:6px;
      padding:10px 24px;cursor:pointer;font-size:15px}
    .btn-send:hover{filter:brightness(1.1)}
  </style>
</head>

<body>
<!-- ============================ HEADER =================================== -->
<?php include 'header.php'; ?>

<!-- ============================ MAIN ===================================== -->
<main>
  <div class="chat-container">
    <!-- barre latérale --------------------------------------------------- -->
    <aside class="chat-sidebar">
      <h2>Conversations</h2>
      <input class="search-input" placeholder="Rechercher…">

      <div class="conversation-item selected" data-name="Marie Curie">
        <p class="conversation-name">Marie Curie</p>
        <p class="conversation-preview">À demain !</p>
      </div>

      <div class="conversation-item" data-name="Jean Dupont">
        <p class="conversation-name">Jean Dupont</p>
        <p class="conversation-preview">Très bien, et toi?</p>
      </div>
    </aside>

    <!-- panneau principal -------------------------------------------------->
    <section class="chat-main">
      <div class="chat-header" id="chat-title">Marie Curie</div>

      <div class="chat-messages" id="chat-messages"></div>

      <div class="chat-input">
        <textarea id="message-input" placeholder="Écrivez un message…"></textarea>
        <button id="send-btn" class="btn-send" type="button">Envoyer</button>
      </div>
    </section>
  </div>
</main>

<!-- ============================ FOOTER =================================== -->
<footer class="site-footer">
  <div><h4>À propos de Nutwork</h4><p><a href="./contact.php">Contactez-nous</a></p><p>À propos de nous</p><p><a href="./contact.php">Blog</p><p><a href="./faq.php">FAQ</a></p></div>
  <div><h4>CGU</h4><p><a href="./Mentions.php">Mentions</a></p><p><a href="./cgv.php">CGV</a></p><p>Développement</p></div>
  <div><h4>Aide & Contacts</h4><p>contact@nutwork.com</p><p>28 Rue Notre Dame des Champs, Paris</p></div>
</footer>

<!-- ============================ SCRIPT =================================== -->
<script>
  /* messages fictifs ---------------------------------------------------------*/
  const data = {
    "Marie Curie":[
      {side:"left" ,text:"Salut Marie !"},
      {side:"right",text:"Salut Jean !"}
    ],
    "Jean Dupont":[
      {side:"right",text:"Salut, ça va ?"},
      {side:"left" ,text:"Très bien, et toi ?"}
    ]
  };

  const chatTitle    = document.getElementById('chat-title');
  const chatMessages = document.getElementById('chat-messages');
  const convoItems   = document.querySelectorAll('.conversation-item');
  const textarea     = document.getElementById('message-input');
  const sendBtn      = document.getElementById('send-btn');

  /* --- fonctions ----------------------------------------------------------- */
  function displayConversation(name){
    chatTitle.textContent = name;
    chatMessages.innerHTML = '';                    // purge
    (data[name]||[]).forEach(m=>{
      const div=document.createElement('div');
      div.className='message '+m.side;
      div.innerHTML='<p>'+m.text+'</p>';
      chatMessages.appendChild(div);
    });
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  /* sélection dans la barre latérale --------------------------------------- */
  convoItems.forEach(item=>{
    item.addEventListener('click',()=>{
      document.querySelector('.conversation-item.selected')?.classList.remove('selected');
      item.classList.add('selected');
      displayConversation(item.dataset.name);
    });
  });

  /* envoyer un message ------------------------------------------------------ */
  sendBtn.addEventListener('click',()=>{
    const txt = textarea.value.trim();
    if(!txt) return;
    const name = chatTitle.textContent;
    data[name] = data[name] || [];
    data[name].push({side:'right',text:txt});
    textarea.value='';
    displayConversation(name);
  });

  /* initialisation ---------------------------------------------------------- */
  displayConversation('Marie Curie');
</script>
</body>
</html>