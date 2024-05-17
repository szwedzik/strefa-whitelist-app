@extends('layouts.base')

@section('title', 'Administrator')

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
                            Powrót
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
                                Administrator
                            </h6>

                            <!-- Title -->
                            <h1 class="header-title text-white">
                                Szczegóły podania
                            </h1>

                        </div>
                    </div> <!-- / .row -->
                </div> <!-- / .header-body -->

                <!-- Footer -->
                <div class="header-footer">

                    <form class="mb-4" action="/new" method="post">

                        @csrf

                        <h6 class="header-pretitle text-secondary">
                            Informacje o kandydacie
                        </h6>

                        <!-- Project name -->
                        <div class="form-group">
                            <label>
                                Link do profilu Steam
                            </label>
                            <small class="form-text text-warning">
                                <i class="fe fe-warning"></i> Sprawdź, czy taki profil Steam istnieje.
                            </small>
                            @if(is_numeric($application->steam_url)) 
                                <a href="https://steamcommunity.com/profiles/{{ $application->steam_url }}">https://steamcommunity.com/profiles/{{ $application->steam_url }}</a>
                            @else
                                <a href="{{ $application->steam_url }}">{{ $application->steam_url }}</a>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>
                                Link do profilu na forum
                            </label>
                            <small class="form-text text-warning">
                                <i class="fe fe-warning"></i> Sprawdź, czy taki profil forum istnieje.
                            </small>
                            <a href="{{ $application->forum_name }}">{{ $application->forum_name }}</a>
                        </div>

                        <div class="form-group">
                            <label class="mb-1">
                                Wiek aplikanta
                            </label>
                            @if($age >= 16)
                                <small class="form-text text-success">
                                    <i class="fe fe-check-verified"></i> Kandydat ma ukończone 16 lat.
                                </small>
                            <p class="text-muted">{{ $age }} lat</p>
                            @else
                                <small class="form-text text-danger">
                                    <i class="fe fe-warning"></i> Kandydat nie ma ukończonych 16 lat.
                                </small>
                            <p class="text-muted">{{ $age }} lat</p>
                            @endif
                        </div>

                        <hr class="mt-5 mb-5">

                        <h6 class="header-pretitle text-secondary">
                            Rozeznanie
                        </h6>

                        <div class="form-group">
                            <label>
                                Czym jest dla Ciebie RP?
                            </label>
                            <p class="text-muted">{{ $application->rp_definition }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Opisz swoje doświadczenia z RP i postacie, które do tej pory odgrywałeś(aś).
                            </label>
                            <p class="text-muted">{{ $application->past_characters }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Napisz, jakie postacie zamierzasz odgrywać na naszym serwerze.<br>
                                Uwzględnij ich historię.
                            </label>
                            <p class="text-muted">{{ $application->character_idea }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Streamujesz albo nagrywasz rozgrywkę na swoje kanały?<br>
                                Jeśli tak, pokaż nam nagrania z przykładem Twojego RP.
                            </label>
                            <p class="text-muted">{{ $application->streamer }}</p>
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
                            @if(strpos($application->rules_opinion, 'guidelines') != false)
                                <small class="form-text text-danger">
                                    <i class="fe fe-warning"></i> <i>https://forum.strefarp.pl/guidelines/</i> to regulamin <b>forum</b>, a nie serwera!
                                </small>
                            @endif
                            <p class="text-muted">{{ $application->rules_opinion }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Do czego służą komendy <code>/me</code> i <code>/do</code>?<br>
                                Jak ich używać?
                            </label>
                            <p class="text-muted">{{ $application->me_do }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Czy korzystając z komendy <code>/do</code>, możesz skłamać?
                            </label>
                            <p class="text-muted">{{ $application->do_lying }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Napisz, co to jest <code>OOC</code> i <code>IC</code>.<br>
                                Czym różnią się te strefy?
                            </label>
                            <p class="text-muted">{{ $application->ooc_vs_ic }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Jakim rodzajem czatu jest ten dostępny pod komendą <code>/tweet</code>?<br>
                                Napisz, do czego jest używany.
                            </label>
                            <p class="text-muted">{{ $application->tweet }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Czy zabójstwo innej postaci z zemsty jest dozwolone?
                            </label>
                            <p class="text-muted">{{ $application->revenge_kill }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Wyjaśnij, co to jest <abbr title="Brutally Wounded">BW</abbr>.
                            </label>
                            <p class="text-muted">{{ $application->brutally_wounded }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Wyjaśnij, czym jest metagaming.<br>
                                Czy można go używać w grze?
                            </label>
                            <p class="text-muted">{{ $application->meta_gaming }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Wyjaśnij, czym jest powergaming.<br>
                            </label>
                            <p class="text-muted">{{ $application->power_gaming }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Napisz, kiedy Twoja postać musi zapomnieć o sytuacji, która miała miejsce przed przewiezieniem do szpitala.
                            </label>
                            <p class="text-muted">{{ $application->forget }}</p>
                        </div>

                        <div class="form-group">
                            <label>
                                Twoja gra dostała crasha.<br>
                                Co wtedy robisz, w jaki sposób powiadamiasz o tym administrację?
                            </label>
                            <p class="text-muted">{{ $application->crash }}</p>
                        </div>

                        <hr class="mt-5 mb-5">

                        <!-- Buttons -->
                        <div class="row">
                            <div class="col col-md-3">
                                <a href="/admin/{{ $application->uuid }}/accept" class="btn btn-block btn-info">
                                    <i class="fe fe-random"></i> <b>Zaakceptuj</b> podanie
                                </a>
                            </div>
                            <div class="col col-md-3">
                                <a href="/admin/{{ $application->uuid }}/deny" class="btn btn-block btn-danger">
                                    <i class="fe fe-close"></i> <b>Odrzuć</b> podanie
                                </a>
                            </div>
                            <div class="col col-md-3">
                                <a href="/admin/{{ $application->uuid }}/mark" class="btn btn-block btn-success">
                                    <i class="fe fe-check"></i> <b>Oznacz jako</b> dodane
                                </a>
                            </div>
                            <div class="col col-md-3">
                                <a href="/admin" class="btn btn-block btn-link text-muted">
                                    <i class="fe fe-arrow-left"></i> Powrót do panelu
                                </a>
                            </div>
                        </div>

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