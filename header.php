<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="/assets/css/style.css">

<link type="image/x-icon" sizes="16x16" rel="icon" href="/assets/icon-16.png">
<link type="image/x-icon" sizes="32x32" rel="icon" href="/assets/icon-32.png">
<link type="image/x-icon" sizes="96x96" rel="icon" href="/assets/icon-96.png">

<script src="/assets/js/bootstrap.bundle.min.js" type="module"></script>
<script src="/assets/js/Sortable.min.js" type="module"></script>
<script src="/assets/js/bs-companion.min.js" type="module"></script>
<script type="module">
    import Tags from "./assets/js/tags.js";

    // These need to be define before init
    window["customItemFormat"] = function (item, label) {
        return label + " (" + item.value + ")";
    };
    window["customOnCreate"] = function (option, inst) {
        console.log("new item", option.value, inst);
        // attach tooltip dynamically
        option.setAttribute('title', 'this element is created dynamically');
    };


    // Multiple inits should not matter
    Tags.init("select:not(.ignore-tags)");

    FormValidator.init();
</script>