$(function () {
    $('.download_csv').click(function () {
        var table_data = [];
        var remove_data = '';
        $('.csv').find('tr').each(function (i, el) {
            var table_th = [];
            $(this).children('th').each(function () {
                remove_data = $(this).attr('class');
                if (remove_data.search("remove_data") == -1) { //-1 for not found and otherwise match
                    table_th.push(JSON.stringify($(this).text()));
                }
            });
            if (table_th.length > 0) {
                table_data.push(table_th);
            }

            var table_td = [];
            $(this).children('td').each(function () {
                remove_data = $(this).attr('class');
                if (remove_data.search("remove_data") == -1) { //-1 for not found and otherwise match
                    table_td.push(JSON.stringify($(this).text()));
                }
            });
            if (table_td.length > 0) {
                table_data.push(table_td);
            }


        });
        var csvContent = "data:text/csv;charset=utf-8,";
        var dataString
        table_data.forEach(function (infoArray, index) {

            dataString = infoArray.join(",");
            csvContent += index < table_data.length ? dataString + "\n" : dataString;

        });
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "backup.csv");

        link.click();
    });
});