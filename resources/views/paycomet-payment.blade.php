<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAYCOMET INTEGRATION</title>

    <!-- CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

    <!-- JavaScript -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

</head>
<body>
<div class="container">
    <div class="row" style="justify-content: center;margin-top:12vh ">
        <aside class="col-sm-6">
            <h1>PAYMENT</h1>
            <article class="card">
                <div class="card-body p-5">
                    <form role="form" id="paycometPaymentForm" name="paycometPaymentForm"  action="{{ route('payment.callback') }}" method="POST">
                        @csrf
                        <input type="hidden" name="amount" value="1234">
                        <input type="hidden" data-paycomet="jetID" value="XazDwUtdXpmPC7eAK9P7rdFWTPVVtSuq">
                        <div class="form-group">
                            <label for="username">Full name (on the card)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" name="username" data-paycomet="cardHolderName" placeholder="" required="">
                            </div> <!-- input-group.// -->
                        </div>

                        <div class="form-group">
                            <label for="cardNumber">Card number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                </div>
                                <div id="paycomet-pan" style="padding:0px; height:36px;"></div>
                                <input paycomet-style="width: 100%; height: 21px; font-size:14px; padding-left:7px; padding-top:8px; border:0px;" paycomet-name="pan" paycomet-placeholder="Introduce tu tarjeta...">
                            </div> <!-- input-group.// -->
                        </div>

                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label><span class="hidden-xs">Expiration</span> </label>
                                    <div class="form-inline">
                                        <select class="form-control" style="width:45%" data-paycomet="dateMonth">
                                            <option>MM</option>
                                            <option value="01">01 - January</option>
                                            <option value="02">02 - February</option>
                                            <option value="03">03 - March</option>
                                            <option value="04">04 - April</option>
                                            <option value="05">05 - May</option>
                                            <option value="06">06 - June</option>
                                            <option value="07">07 - July</option>
                                            <option value="08">08 - August</option>
                                            <option value="09">09 - September</option>
                                            <option value="10">10 - October</option>
                                            <option value="11">11 - November</option>
                                            <option value="12">12 - December</option>
                                        </select>
                                        <span style="width:10%; text-align: center"> / </span>
                                        <select class="form-control" style="width:45%" data-paycomet="dateYear">
                                            <option>YY</option>
                                            <option value="20">2020</option>
                                            <option value="21">2021</option>
                                            <option value="22">2022</option>
                                            <option value="23">2023</option>
                                            <option value="24">2024</option>
                                            <option value="25">2025</option>
                                            <option value="26">2026</option>
                                            <option value="27">2027</option>
                                            <option value="28">2028</option>
                                            <option value="29">2029</option>
                                            <option value="30">2030</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label data-toggle="tooltip" title=""
                                           data-original-title="3 digits code on back side of the card">CVV <i
                                            class="fa fa-question-circle"></i></label>
                                    <div id="paycomet-cvc2" style="height: 36px; padding:0px;"></div>
                                    <input paycomet-name="cvc2" paycomet-style="border:0px; width: 100%; height: 21px; font-size:12px; padding-left:7px; padding-tap:8px;" paycomet-placeholder="CVV2" class="form-control" required="" type="text">
                                </div> <!-- form-group.// -->
                            </div>

                        </div>
                        <button class="subscribe btn btn-primary btn-block" type="submit"> Confirm </button>
                    </form>

                    <div id="paymentErrorMsg"></div>
                </div>
            </article>
        </aside>
    </div>
</div>

<!-- Paycomet JS -->
<script src="https://api.paycomet.com/gateway/paycomet.jetiframe.js?lang=es"></script>

</body>
</html>
