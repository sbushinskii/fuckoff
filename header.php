<link href="assets/css/bootstrap.min.css" rel="stylesheet" />

<script src="assets/js/bootstrap.bundle.min.js" type="module"></script>
<script src="assets/js/Sortable.min.js" type="module"></script>
<script src="assets/js/bs-companion.min.js" type="module"></script>
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