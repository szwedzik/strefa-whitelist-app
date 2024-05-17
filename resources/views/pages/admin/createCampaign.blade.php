<html>
<head>
<meta name="csrf"
</head>
<body>
<style>
    html, body, input::placeholder, textarea::placeholder {
        font-family: "Consolas", sans-serif;
    }

    input, textarea, button {
        width: 100%;
        padding: 1rem;
        margin: .5rem 0 .5rem 0;
    }

    button {
        font-weight: bold;
        text-transform: uppercase;
    }
</style>
<center>
    <h2>Roboczy formularz dodawania kampanii</h2>
    <p style="color: red;">Zero walidacji, więc uważaj, co tu kurwa wpisujesz!</p>
</center>
<hr>
<form action="/admin/campaign/new" method="post">
    @csrf
    <input name="name" placeholder="Nazwa"><br>
    <input name="short_description" placeholder="Krótki opis"><br>
    <textarea name="description" placeholder="Długi opis"></textarea><br>
    <input name="available" placeholder="Dostępna dla userów (0/1)"><br>
    <input name="who_can_check" placeholder="Discord ID sprawdzających podania (id1,id2,id3...)"><br>
    <input name="ips_group_on_accept" placeholder="ID grupy forum po akceptacji"><br>
    <input name="discord_role_on_accept" placeholder="ID roli discord po akceptacji"><br>
    <button type="submit">Wpierdol do bazy</button>
</form>
</body>
</html>