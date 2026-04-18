function pollingTrade(tradeNo) {
    var timer = setInterval(function () {
        $.get('/api/cashier/trade?tradeNo=' + tradeNo, function(response) {
            var trade = response.data;
            if (trade.status === 'PAY_FAILED') {
                clearInterval(timer);
                (window.jsInterface && window.jsInterface.onNavBack()) || window.history.back();
            }

            if (trade.status === 'PAID') {
                clearInterval(timer);
                if (trade.redirectUrl != null && trade.redirectUrl != '') {
                    location.href = trade.redirectUrl;
                }
                (window.jsInterface && window.jsInterface.onNavBack()) || window.history.back();
            }
        }, 'json');
    }, 15000);
}