<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        h4 {
            margin: 0;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .margin-top {
            margin-top: 1.25rem;
        }

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241 245 249);
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        table.products {
            font-size: 0.875rem;
        }

        table.products tr {
            background-color: rgb(96 165 250);
        }

        table.products th {
            color: #ffffff;
            padding: 0.5rem;
        }

        table tr.items {
            background-color: rgb(241 245 249);
        }

        table tr.items td {
            padding: 0.5rem;
        }

        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <img src="{{ asset('images/bidTN.png') }}" alt="BID-TN" width="200" />
                </td>
                <td class="w-half">
                    <h2>Invoice ID: {{ $transaction->id }}</h2>
                </td>
            </tr>
        </table>

        <div class="margin-top">
            <table class="w-full">
                <tr>
                    <td class="w-half">
                        <div>
                            <h4>To:</h4>
                        </div>
                        <div>{{ $user->first_name . ' ' . $user->last_name }}</div>
                        <div>{{ $user->email }}</div>
                    </td>
                    <td class="w-half">
                        <div>
                            <h4>From:</h4>
                        </div>
                        <div>BID-TN</div>
                        <div>I Imm Espace, Rue de Khartoum, Sousse 4000</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="margin-top">
            <table class="products">
                <tr>
                    <th>Qty</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
                @if($transaction_type == 'credit')
                <tr class="items">
                    <td>1</td>
                    <td>{{ $pack->name }}</td>
                    <td>${{ number_format($pack->price, 1) }}</td>
                </tr>
                @else
                <tr class="items">
                    <td>1</td>
                    <td>Debit Transaction</td>
                    <td>${{ number_format($amount, 1) }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="total">
            Total: ${{ number_format($transaction_type == 'credit' ? $pack->price : $amount, 1) }} USD
        </div>

        <div class="footer margin-top">
            <div>Thank you</div>
            <div>&copy; BID-TN</div>
        </div>
    </div>
</body>

</html>
