<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(4) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(4) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .rtl table {
        text-align: right;
    }

    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <img src="https://via.placeholder.com/150?text=Company%20Logo">
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                            <strong>Invoice #: {{ $data['invoice_no'] }}</strong><br>
                                {{date("F j, Y, g:i a",strtotime($data['rent_date']))}}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                {{ $data['from'] }}
                            </td>
                            <td></td>
                            <td></td>
                            <td>
                            {{ $data['to']['name'] }}<br>
                            {{ $data['to']['email'] }}<br>
                            {{ $data['billing_info'] }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>
                    Book
                </td>
                <td>
                    Author
                </td>
                <td>
                    Date of Issue
                </td>
                <td>
                    Price
                </td>
            </tr>

            <tr class="item">
                <td>
                    {{ $data['item']->name }}
                </td>
                <td>
                    {{ $data['item']->author }}
                </td>
                <td>
                    {{date("d M Y",strtotime($data['rent_date']))}}
                </td>

                <td>
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ number_format($data['item']->price,2) }}
                </td>
            </tr>

            <tr class="total">
                <td></td>
                <td></td>
                <td></td>
                <td>
                   Total: <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{ number_format($data['item']->price,2) }}
                </td>
            </tr>
            <tr>
                <td>&nbsp;
                </td>
                <td>&nbsp;</td>
            </tr>

            <tr class="heading">
                <td colspan="2">
                    Payment Method
                </td>
                <td></td>
            </tr>

            <tr class="details">
                <td colspan="2">
                    {{ $data['payment_info'] }}
                </td>
                <td></td>
            </tr>
        </table>
    </div>
</body>
</html>
