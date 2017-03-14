(function($) {
    var $collectionHolder;

    // setup an "add link" link
    var $addLinkLink = $('<a href="#" class="btn btn-success">Add link</a>');
    var $newLinkLi = $('<li></li>').append($addLinkLink);

    $(document).ready(function() {
        // Get the ul that holds the collection of links
        $collectionHolder = $('ul#snippet_links');

        $collectionHolder.find('li').each(function() {
            addLinkFormDeleteLink($(this));
        });

        // add the "add link" anchor and li to the links ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        $addLinkLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new link form (see next function)
            addLinkForm($collectionHolder, $newLinkLi);
        });
    });

    function addLinkForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add link" link li
        var $newFormLi = $('<li></li>').append(newForm);

        addLinkFormDeleteLink($newFormLi);

        $newLinkLi.before($newFormLi);
    }

    function addLinkFormDeleteLink($linkFormLi) {
        var $removeFormA = $('<a href="#" class="btn btn-xs btn-danger pull-right">Delete link</a>');
        $linkFormLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the link form
            $linkFormLi.remove();
        });
    }

})(jQuery);
