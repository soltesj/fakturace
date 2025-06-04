import http from 'k6/http';
import {check} from 'k6';
import {parseHTML} from 'k6/html';

export const options = {
    thresholds: {
        http_req_duration: ['p(95)<1500'],
    },
    stages: [
        {duration: '30s', target: 20},
        {duration: '30s', target: 50},
        {duration: '20s', target: 0},
    ],
};

export default function () {
    const baseUrl = 'http://k6.fakturace.local';

    let res = http.get(`${baseUrl}/cs/login`);
    const jar = http.cookieJar();
    let doc = parseHTML(res.body);
    let csrfToken = doc.find('input[name="_csrf_token"]').attr('value');

    let payload = {
        email: 'user1@example.com',
        password: 'password123',
        _csrf_token: csrfToken,
    };

    let loginRes = http.post(`${baseUrl}/cs/login`, payload, {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Referer': `${baseUrl}/cs/login`,
            'Origin': baseUrl,
        },
        jar: jar, redirects: 0
    });

    check(loginRes, {
        'Login success': (r) => r.headers['Location'] === '/cs/1/dashboard' && r.status === 302,
    });


    let invoiceRes = http.get(`${baseUrl}/cs/1/document/new`, {jar});
    let invoiceDoc = parseHTML(invoiceRes.body);
    let invoiceCsrfToken = invoiceDoc.find('input[name="document_form[_token]"]').attr('value');

    const date = getRandomDate();
    let payloadInvoice = {
        'document_form[documentNumber]': '2025110002',
        'document_form[variableSymbol]': '',
        'document_form[paymentType]': '1',
        'document_form[dateIssue]': date,
        'document_form[dateTaxable]': date,
        'document_form[dateDue]': date,
        'document_form[bankAccount]': '1',
        'document_form[customer]': '1',
        'document_form[currency]': 1,
        'document_form[note]': 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam luctus egestas nisi sit amet porttitor. Integer ac aliquam turpis. Sed vehicula pretium sapien sit amet convallis. ',
        'document_form[description]': 'Fakturujeme Vám služby dle Vaší objednávky:',
        'document_form[vatMode]': 'domestic',
        'document_form[documentItems][0][name]': 'Item test 1',
        'document_form[documentItems][0][quantity]': '2',
        'document_form[documentItems][0][price]': '150',
        'document_form[documentItems][0][unit]': 'ks',
        'document_form[documentItems][0][vat]': '1',
        'document_form[documentItems][1][name]': 'Item test 1',
        'document_form[documentItems][1][quantity]': '2',
        'document_form[documentItems][1][price]': '150',
        'document_form[documentItems][1][unit]': 'ks',
        'document_form[documentItems][1][vat]': '1',
        'document_form[_token]': invoiceCsrfToken,
    };

    const encodedPayload = Object.entries(payloadInvoice)
        .map(([k, v]) => `${encodeURIComponent(k)}=${encodeURIComponent(v)}`)
        .join('&');

    invoiceRes = http.post(`${baseUrl}/cs/1/document/new`, encodedPayload, {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Referer': `${baseUrl}/cs/1/document/new`,
            'Origin': baseUrl,
        },
        jar: jar, redirects: 0
    });

    if (!check(invoiceRes, {
        'Invoice stored response status is 200': (r) => r.status === 303,
    })) {
        console.log(invoiceRes.status)
        console.log(invoiceRes.body)
    }

}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

function getRandomDate() {
    const month = getRandomInt(12) + 1
    const day = getRandomInt(28) + 1
    return `2025-${month}-${day}`;
}