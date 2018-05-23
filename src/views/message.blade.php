@extends('base')

@section('content')
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-8 offset-md-2">
                <div class="card" style="max-width: 40rem">
                    <div class="card-body">
                        <h5 class="card-title">Member agreement</h5>
                        <h6 class="card-subtitle mb-2 text-muted">GDPR adjustment</h6>
                        <p class="card-text">Lorem ipsum dolor sit amet, pro in cetero audire persius, et nam viderer
                            placerat explicari, atqui nusquam ea vel. Hinc denique ea has. No his exerci eloquentiam
                            vituperatoribus,
                            iriure petentium expetenda in pri. Et quod debet fabellas per, mel timeam antiopam
                            vituperatoribus ad. Possim minimum eu nam.
                            Ne duo simul putent. Ullum periculis vituperata cu sit. Ex vel cibo semper accusam, eum
                            dolorum percipit et. Ridens tacimates ullamcorper ut usu, movet quaerendum persequeris ad
                            mel.
                            Officiis lobortis salutatus ei vis, ad denique qualisque sententiae mel. Justo graece sea
                            ei, vero modus propriae ea mei.</p>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group float-left">
                                    <form class="form-inline" role="form" method="POST"
                                          action="{{ route('terms_accepted') }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-primary">Accept</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group float-right">
                                    <form class="form-inline float-left" role="form" method="POST"
                                          action="{{ route('terms_denied') }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-outline-danger">Deny</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop