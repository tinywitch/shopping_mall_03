function autoComplete (){
    var searchs = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: '/getDataSearch'
    });

    searchs.initialize();

    $('#search-form #search-box').typeahead({},
    {
        name: 'products',
        display: 'name',
        source: searchs.ttAdapter(),
        templates: {
            empty: [
                '<div class="empty-message">',
                    'there no match result',
                '</div>'
            ].join('\n'),
            suggestion: Handlebars.compile(
                '<div class="tt-suggestion tt-selectable"><a href="/product/view/{{id}}"><img src="{{image}}" class="image-product-icon">{{name}}</a></div>'
            )
        }
    });
}

$(document).ready(autoComplete);
$(document).on('page:load', autoComplete);
