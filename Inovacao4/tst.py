# First, let's define the translations in Portuguese for the song "Crazy for You".
# We will add them in addition to the current kanji and romaji lyrics.

translations = [
    "Entregue esses sentimentos",
    "O céu noturno é sua pista de decolagem",
    "Sua voz faz meu coração tremer",
    "Por favor, não me torture mais",
    "Eu não consigo jogar esses jogos",
    "Quero te ver agora, estou louca por você",
    "Percebi que estava cochilando",
    "Sozinha em uma noite como esta",
    "Com o celular apertado nas mãos",
    "Seu nome na chamada perdida",
    "Quero te ver, não posso te ver, estou frustrada",
    "Quero saber, quero tocar...",
    "Quem roubou meu coração foi você",
    "Entregue esses sentimentos",
    "O céu noturno é sua pista de decolagem",
    "Sua voz faz meu coração tremer",
    "Por favor, não me torture mais",
    "Eu não consigo jogar esses jogos",
    "Quero te ver agora, estou louca por você",
    "Será que consigo dizer amanhã?",
    "Tenho certeza que se for por isso, eu...",
    "Posso até me tornar uma garota um pouco malvada",
    "Todo o meu corpo está tremendo, sinto sua falta",
    "Quero ouvir sua voz, meu coração está acelerado",
    "Não consigo esperar, preciso te contar...",
    "Ah, estou prestes a sair correndo",
    "Parem, todas essas ansiedades",
    "Em que tipo de sonhos você está agora?",
    "Minha cabeça está cheia de você",
    "Olhar nos seus olhos não é o suficiente",
    "Quero chegar mais perto",
    "Essa é a primeira vez que posso ser tão honesta",
    "Entregue esses sentimentos",
    "O céu noturno é sua pista de decolagem",
    "Sua voz faz meu coração tremer",
    "Por favor, não me torture mais",
    "Eu não consigo jogar esses jogos",
    "Quero te ver agora, estou louca por você",
    "Quero te ver agora, estou louca por você",
    "Rarara rarara rararararara...."
]

lyrics = [
    ("Todoke kono omoi yo", "届け　この思いよ"),
    ("Yozora wa kimi e no kassouro", "夜空はキミへの滑走路"),
    ("Haato o yurasu yo kimi no koe", "ハートを揺らすよ　キミの声"),
    ("Onegai kore ijou jirasanaide", "お願い　これ以上　じらさないで"),
    ("Kakehiki nante dekinakutte", "かけ引きなんてできなくって"),
    ("Ima sugu aitai yo I'm just crazy for you", "今すぐ会いたいよ　I'm just crazy for you"),
    ("Kizuitara utouto shiteta mitai", "気付いたら ウトウトしてたみたい"),
    ("Konna mayonaka hitorikiri", "こんな真夜中　ひとりきり"),
    ("Keitai nigirishimeteta te ni", "ケータイ握りしめてた手に"),
    ("Nokoru chakushin kimi no namae", "残る着信　キミの名前"),
    ("Aitai, aenai, modokashikutte", "会いたい、会えない、もどかしくって"),
    ("Shiritai, furetai...", "知りたい、触れたい…"),
    ("Kokoro ubatta no wa kimi nanda", "心奪ったのはキミなんだ"),
    ("Todoke kono omoi yo", "届け　この思いよ"),
    ("Yozora wa kimi e no kassouro", "夜空はキミへの滑走路"),
    ("Haato o yurasu yo kimi no koe", "ハートを揺らすよ　キミの声"),
    ("Onegai kore ijou jirasanaide", "お願い　これ以上　じらさないで"),
    ("Kakehiki nante dekinakutte", "かけ引きなんてできなくって"),
    ("Ima sugu aitai yo I'm just crazy for you", "今すぐ会いたいよ　I'm just crazy for you"),
    ("Nee ashita koso wa ieru kana", "ねえ明日こそは言えるかな"),
    ("Kitto sono tame nara watashi", "きっとそのためならわたし"),
    ("Chotto zurui ko ni datte narisou", "ちょっとズルいコにだってなりそう"),
    ("Zenshin I miss you furuedashita", "全身　I miss you　震えだした"),
    ("Koe o, kikitai, takanaru kodou", "声を、聴きたい、高鳴る鼓動"),
    ("Matenai, tsutaetai...", "待てない、伝えたい…"),
    ("Aa tobidashite shimaisou nan da", "ああ飛び出してしまいそうなんだ"),
    ("Tomare kono fuan yo", "止まれ　この不安よ"),
    ("Imagoro donna yumemiteru no?", "今頃どんな夢見てるの?"),
    ("Atama no naka wa kimi darake", "頭の中はキミだらけ"),
    ("Me ga au dake ja tarinakutte", "目が合うだけじゃ足りなくって"),
    ("Motto chikazuite mitakutte", "もっと近づいてみたくって"),
    ("Konna ni sunao ni nareru tte hajimete kamo", "こんなに素直になれるってはじめてかも"),
    ("Todoke kono omoi yo", "届け　この思いよ"),
    ("Yozora wa kimi e no kassouro", "夜空はキミへの滑走路"),
    ("Haato o yurasu yo kimi no koe", "ハートを揺らすよ　キミの声"),
    ("Onegai kore ijou jirasanaide", "お願い　これ以上　じらさないで"),
    ("Kakehiki nante dekinakutte", "かけ引きなんてできなくって"),
    ("Ima sugu aitai yo I'm just crazy for you", "今すぐ会いたいよ　I'm just crazy for you"),
    ("Ima sugu aitai yo I'm just crazy for you", "今すぐ会いたいよ　I'm just crazy for you"),
    ("Rarara rarara rararararara....", "ラララ　ラララ　ララララララ...")
]

# Now we will modify the SRT creation code to duplicate the timestamps and include the translations

srt_file_content_with_translation = ""
start_time = 0.0  # Starting timestamp in seconds
duration = 5.0   # Duration each subtitle stays on screen in seconds

for i, ((romaji, kanji), (translation, _)) in enumerate(zip(lyrics, translations), start=1):
    # Convert start and end time to SRT format (hours:minutes:seconds,milliseconds)
    start_time_srt = f"{int(start_time // 3600):02}:{int((start_time % 3600) // 60):02}:{int(start_time % 60):02},{int((start_time % 1) * 1000):03}"
    end_time = start_time + duration
    end_time_srt = f"{int(end_time // 3600):02}:{int((end_time % 3600) // 60):02}:{int(end_time % 60):02},{int((end_time % 1) * 1000):03}"

    # Append the original kanji and romaji lines
    srt_file_content_with_translation += f"{i}\n{start_time_srt} --> {end_time_srt}\n{kanji}\n{romaji}\n\n"
    
    # Increment the index for the translation and set the same time interval
    i += 1
    srt_file_content_with_translation += f"{i}\n{start_time_srt} --> {end_time_srt}\n{translation}\n\n"
    
    # Move to the next start time
    start_time = end_time

# Save the new SRT file with translations
file_path_with_translation = "/mnt/data/crazy_for_you_with_translation.srt"
with open(file_path_with_translation, "w", encoding="utf-8") as srt_file:
    srt_file.write(srt_file_content_with_translation)

file_path_with_translation<div id="perfilPopup" class="perfil-popup">
        <h4>Perfil do Usuário</h4>
        <ul>
            <li><a href="<?php echo ROOT_PATH; ?>/Paginas/Perfil.php">Ver Perfil</a></li>
            <li><a href="#">Configurações</a></li>
            <li><a href="<?php echo ROOT_PATH; ?>/Paginas/logout.php">Sair</a></li>
        </ul>
    </div>

