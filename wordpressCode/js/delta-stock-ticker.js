jQuery(document).ready(function() {   

    if (typeof(arrayOfStocks) === 'undefined') {
        return;
    } else {
        jQuery.each(arrayOfStocks, function() {
            getAStock(this);
        });
    }
});

function getAStock(stockToGet) {
    jQuery.getJSON('https://finance.google.com/finance/info?client=ig&q=NYSE:'+stockToGet+'&callback=?',
        function(response){
        var stockInfo = response[0];
        if (stockInfo.c > 0) {
            var stockString = '<span class="up">';
        } else {
            var stockString = '<span class="down">';
        }

        stockString += '<span class="quote"> '+stockInfo.t+'</span>';
        stockString += stockInfo.l+' ';
        stockString += stockInfo.c+' ';
        stockString += '</span>';
        jQuery('.stockTicker').append(stockString);
    });
} 