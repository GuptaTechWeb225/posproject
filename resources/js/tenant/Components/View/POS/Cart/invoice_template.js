import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export const generateInvoiceTemplate = (generalSettings) => {
    const itemDetails = generalSettings.cartItems.map(item => `
         <tr>
            <td>
                <div>${item.name}</div> 
                <div>${item.quantity}</div> 
                <div></div>  
            </td>
            <td class="text-right">${numberWithCurrencySymbol(item.selling_price)}</td>
        </tr>
    `)

    return `
        <div class="tharmal-invoice">
            <div class="tharmal-invoice__item tharmal-invoice__item--header">
                <div>
                    <img class="logo" src="${generalSettings.logo_source}" /> 
                </div>
                <p>Address: ${generalSettings.company_address}</p>
                <p>Phone: ${generalSettings.company_phone}</p>
                <p>Email: ${generalSettings.company_email}</p>
            </div>

            <div class="tharmal-invoice__item">
                <p>Date: ${generalSettings.date}</p>
                <p>Cash Register: ${generalSettings.cash_register}</p>
                <p>Customer: ${generalSettings.customer_name}</p>
            </div>

            <div class="tharmal-invoice__item tharmal-invoice__item--body">
                <table>
                    <tbody>
                    <tr style="border-bottom: 2px dotted #000;">
                        <th class="text-left">Products</th>
                        <th class="text-right" style="width: 20%;">Sub total</th>
                    </tr>
                    <tr>
                        <td>
                            ${itemDetails}
                        </td> 
                    </tr>
                    <tr style="border-top: 2px dotted #000;">
                        <td style="padding: 10px 0 0 0;">Sub-total</td>
                        <td class="text-right" style="padding: 10px 0 0 0;">${generalSettings.sub_total ? numberWithCurrencySymbol(generalSettings.sub_total) : 'sub_total'}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0;">Discount on subtotal</td>
                        <td class="text-right" style="padding: 0;">${numberWithCurrencySymbol(generalSettings.discount)}</td>
                    </tr>
                    <tr>
                        <td style="padding: 0 0 10px 0;">Tax</td>
                        <td class="text-right" style="padding: 0 0 10px 0;">${numberWithCurrencySymbol(generalSettings.tax)}</td>
                    </tr>
                    <tr style="border-top: 2px dotted #000;">
                        <th class="text-left">Grand Total</th>
                        <th class="text-right">${generalSettings.total ? numberWithCurrencySymbol(generalSettings.total) : 'total'}</th>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="tharmal-invoice__item tharmal-invoice__item--footer">
                <div>
                    <p>Thank you for shopping with us</p>
                    <p>Please come again</p>
                </div>
            </div>
        </div>
`
}

