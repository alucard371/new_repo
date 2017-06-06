var $collectionHolder;

// setup an "add a ticket" link
var $addTicketLink = $('<a href="#"  class="add_ticket_link"><button onclick="show()" class="btn btn-default">Ajouter un billet</button></a>');
var $newLinkLi = $('<li class="list-unstyled"></li>').append($addTicketLink);

$(document).ready(function() {




    // Get the ul that holds the collection of billets
    $collectionHolder = $('ul.billets');

    // add a delete link to all of the existing billet from li elements
    $collectionHolder.find('li').each(function () {
        addBilletFormDeleteLink($(this))
    });

    // add the "add a billet" anchor and li to the billets ul
    $collectionHolder.append($newLinkLi);



    // count the current form inputs we have, use that as the new
    // index when inserting a new item
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTicketLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new billet form (see next code block)
        addBilletForm($collectionHolder, $newLinkLi);
    });
});

function addBilletFormDeleteLink($billetFormLi) {
    var $removeFormA = $('<a href="#"><button class="btn btn-danger">Supprimer le billet</button></a>');
    $billetFormLi.append($removeFormA);

    $removeFormA.on('click', function (e) {
        //prevent the link from creating a '#' on the URL
        e.preventDefault();

        //remove the li from the billet form
        $billetFormLi.remove();
    })

}


function addBilletForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="list-unstyled"></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addBilletFormDeleteLink($newFormLi);
}
