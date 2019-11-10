$(document).ready(function() {
    $('.list-page').each(function(i, v) {
        var $list = $(this);
        search = searchWord.length ? searchWord : '';
        $('.pet_name', $list).mark(search, {
            className: 'highlight',
            separateWordSearch: true,
            accuracy: "partially",
        });
    });
});