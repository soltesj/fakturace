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
        {duration: '30s', target: 100},
        {duration: '1m', target: 100},
        {duration: '20s', target: 0},
    ],
};

export default function () {
    const baseUrl = 'http://k6.fakturace.local';

    // 1. GET login page
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

    let redirectTo = loginRes.headers['Location'];

    if (redirectTo) {
        let dashboardRes = http.get(`${baseUrl}${redirectTo}`, {jar});
        check(dashboardRes, {
            'dashboard response status is 200': (r) => r.status === 200,
        });
    }
}