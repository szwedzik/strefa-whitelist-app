@extends('layouts.base')

@section('title', 'Nowe podanie')

@section('body')
    <body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">

            <!-- Toggler -->
            <button class="navbar-toggler mr-auto" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>



            <!-- User -->
            <div class="navbar-user">

                <!-- Dropdown -->
                <div class="dropdown">

                    <!-- Toggle -->
                    <a href="#" class="avatar avatar-sm dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- https://cdn.discordapp.com/avatars/412867223925948428/f3242bdaf443ffdfc793385deb6661d6.png?size=2048 -->
                        <img src="{{ $profilePicture }}" alt="..." class="avatar-img rounded-circle">
                    </a>

                    <!-- Menu
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="profile-posts.html" class="dropdown-item"><span class="fe fe-lock"></span> Panel administratora</a>
                        <a href="#" class="dropdown-item"><span class="fe fe-eye"></span> Sprawdź podania</a>
                        <hr class="dropdown-divider">
                        <a href="sign-in.html" class="dropdown-item">Wyloguj się</a>
                    </div> -->

                </div>

            </div>

            <!-- Collapse -->
            <div class="collapse navbar-collapse mr-auto order-lg-first" id="navbar">

                <!-- Navigation -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <img src="/assets/img/logo.png" class="navbar-brand-img">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            Strona główna
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/new">
                            Napisz podanie
                        </a>
                    </li>
                    <!--<li class="nav-item">
                        <a class="nav-link" href="/help">
                            Pomoc
                        </a>
                    </li>-->
                </ul>

            </div>

        </div>
    </nav>

    <div class="main-content">

        <div class="header bg-dark pb-5">
            <div class="container">
                <div class="header-body">
                    <div class="row align-items-end">

                        <div class="col">

                            <!-- Pretitle -->
                            <h6 class="header-pretitle text-secondary">
                                Podanie
                            </h6>

                            <!-- Title -->
                            <h1 class="header-title text-white">
                                Wypełnij formularz podania
                            </h1>

                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .header-body -->

                <!-- Footer -->
                <div class="header-footer">

                    <form class="mb-4" action="/new" method="post">

                        @csrf

                        <h6 class="header-pretitle text-secondary">
                            Informacje o Tobie
                        </h6>

                        <!-- Project name -->
                        <div class="form-group">
                            <label>
                               Twój SteamID64
                            </label>
                            <small id="emailHelp" class="form-text text-muted">Jeśli nie wiesz co to SteamID64, wejdź w ten <a href="https://forum.strefarp.pl/SteamConverter">link</a> by przejść do SteamConverter, gdzie zmienisz swój steamid na steamid64. Lub wejdź na stronę <a href="https://steamid.io">SteamID.io</a> gdzie wklejając link do twojego profilu, otrzymasz potrzebne dane. 😊</small>
                            <input type="number" class="form-control" name="steam_url" minlength=17 required>

                        </div>

                        <div class="form-group">
                            <label>
                                Link do profilu na forum
                            </label>
                            <input required type="text" class="form-control" name="forum_name">
                        </div>

                        <div class="form-group">
                            <label class="mb-1">
                                Data urodzenia
                            </label>
                            <small class="form-text text-muted">
                                Musisz mieć co najmniej 16 lat, by zagrać na Strefie RP.
                            </small>
                            <input required type="date" class="form-control" name="age">
                        </div>

                        <hr class="mt-5 mb-5">

                        <h6 class="header-pretitle text-secondary">
                            Rozeznanie
                        </h6>

                        <div class="form-group">
                            <label>
                                Czym jest dla Ciebie RP?
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="rp_definition"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Opisz swoje doświadczenia z RP i postacie, które do tej pory odgrywałeś(aś).
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="past_characters"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Napisz, jakie postacie zamierzasz odgrywać na naszym serwerze.<br>
                                Uwzględnij ich historię.
                            </label>
                            <textarea maxlength="4500" required type="text" class="form-control" name="character_idea"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Streamujesz albo nagrywasz rozgrywkę na swoje kanały?<br>
                                Jeśli tak, pokaż nam nagrania z przykładem Twojego RP.
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="streamer"></textarea>
                        </div>

                        <hr class="mt-5 mb-5">

                        <h6 class="header-pretitle text-secondary">
                            Serwer i rozgrywka
                        </h6>

                        <div class="form-group">
                            <label>
                                Wyślij link do regulaminu <b>serwera</b>.<br>
                                Wskaż jego dobre i złe strony.
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="rules_opinion"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Do czego służą komendy <code>/me</code> i <code>/do</code>?<br>
                                Jak ich używać?
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="me_do"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Czy korzystając z komendy <code>/do</code>, możesz skłamać?
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="do_lying"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Napisz, co to jest <code>OOC</code> i <code>IC</code>.<br>
                                Czym różnią się te strefy?
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="ooc_vs_ic"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Jakim rodzajem czatu jest ten dostępny pod komendą <code>/tweet</code>?<br>
                                Napisz, do czego jest używany.
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="tweet"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Czy zabójstwo innej postaci z zemsty jest dozwolone?
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="revenge_kill"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Wyjaśnij, co to jest <abbr title="Brutally Wounded">BW</abbr>.
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="brutally_wounded"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Wyjaśnij, czym jest metagaming.<br>
                                Czy można go używać w grze?
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="meta_gaming"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Wyjaśnij, czym jest powergaming.<br>
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="power_gaming"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Napisz, kiedy Twoja postać musi zapomnieć o sytuacji, która miała miejsce przed przewiezieniem do szpitala.
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="forget"></textarea>
                        </div>

                        <div class="form-group">
                            <label>
                                Twoja gra dostała crasha.<br>
                                Co wtedy robisz, w jaki sposób powiadamiasz o tym administrację?
                            </label>
                            <textarea maxlength="2000" required type="text" class="form-control" name="crash"></textarea>
                        </div>

                        <!-- Divider -->
                        <hr class="mt-5 mb-5">

                        <div class="card bg-light border">
                            <div class="card-body">

                                <h4 class="mb-2">
                                    <span class="fe fe-warning"></span> Uwaga
                                </h4>

                                <p class="small text-muted mb-0">
                                    Zapisz swoją aplikację na komputerze na wypadek utracenia połączenia internetowego albo problemów z systemem podań.<br>
                                    Do tego celu możesz użyć np. wbudowanego w system Notatnika.<br><br>
                                    Pamiętaj, że po wysłaniu aplikacji, nie możesz edytować odpowiedzi. Jej sprawdzenie potrwa do 7 dni roboczych.
                                </p>

                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="mt-5 mb-5">

                        <!-- Buttons -->
                        <button type="submit" class="btn btn-block btn-primary">
                            Wyślij podanie
                        </button>
                        <a href="/" class="btn btn-block btn-link text-muted">
                            Rozmyśliłem się, anuluj
                        </a>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript libraries -->
    <script src="/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/chart.js/dist/Chart.min.js"></script>
    <script src="/assets/libs/chart.js/Chart.extension.min.js"></script>
    <script src="/assets/libs/highlight/highlight.pack.min.js"></script>
    <script src="/assets/libs/flatpickr/dist/flatpickr.min.js"></script>
    <script src="/assets/libs/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
    <script src="/assets/libs/list.js/dist/list.min.js"></script>
    <script src="/assets/libs/quill/dist/quill.min.js"></script>
    <script src="/assets/libs/dropzone/dist/min/dropzone.min.js"></script>
    <script src="/assets/libs/select2/dist/js/select2.min.js"></script>

    <!-- Theme script -->
    <script src="/assets/js/theme.min.js"></script>

    </body>
@endsection