<?php
require_once '../database.php';
$db = new Database();

$tags = $db->getTags();

if(!empty($_POST)) {
    foreach ($_POST['tags_new'] as $node) {
        if(!(int)$node+0){
            $record = [
                    'title'=>$node
            ];
            $db->insert('tags',$record);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en" class="no-js" data-bs-theme="">
  <head>

    <link href="bootstrap.min.css" rel="stylesheet" />

    <script src="bootstrap.bundle.min.js" type="module"></script>
    <script src="Sortable.min.js" type="module"></script>
    <script src="bs-companion.min.js" type="module"></script>
    <script type="module">
      import Tags from "./tags.js";

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

      document.addEventListener("change", (event) => {
        console.log(`document listener: #${event.target.id}`);
      });

      document.querySelector("#show_suggestions").addEventListener("click", (ev) => {
        ev.preventDefault();

        const el = document.getElementById("inputGroupTags");
        /** @type {Tags} */
        let inst = Tags.getInstance(el);
        inst.toggleSuggestions(false);
      });

      document.querySelector('#add_option').addEventListener("click", (ev) => {
        ev.preventDefault();

        const input = document.getElementById('new_option');
        const v = input.value;
        if(!v) {
          alert('No value');
          return;
        }
        const el = document.getElementById('validationTagsNew');
        const inst = Tags.getInstance(el);
        inst.addItem(v);
      })

      FormValidator.init();
    </script>

  </head>

  <body>
    <div id="main" style="display: none"></div>
    <div class="container">
      <h1>Demo</h1>

      <button id="darkmode">Toggle dark mode</button>

      <form class="needs-validation" method="POST">
        <div class="row mb-3 g-3">
          <div class="col-md-4">
            <label for="validationTagsNewSame" class="form-label">Tags (allow new + same)</label>
            <select class="form-select" id="validationTagsNewSame" name="tags_new[]" multiple data-allow-new="true" data-allow-same="true">
              <option disabled hidden value="">Choose a tag...</option>
                <?php foreach ($tags as $tag) { ?>
                    <option value="<?php echo $tag['id'];?>"><?php echo $tag['title'];?></option>
                <?php
                }
                ?>
            </select>
          </div>
        </div>

        <button class="btn btn-primary" type="submit">Submit form</button>
      </form>
    </div>
  </body>
</html>
