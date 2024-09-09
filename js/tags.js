var base_url = $("#custom-js").data("base-url");

$('input[name="proposal_tags"]').amsifySuggestags({
    suggestionsAction: {
        url: base_url + '/tags_suggestion.php',
        beforeSend: function () {
            console.info('beforeSend');
        },
        success: function (data) {
            console.info(data);
        },
        error: function () {
            console.info('error');
        },
        complete: function (data) {
            console.info('complete');
        }
    },
    trimValue: true,
    printValues : false
});