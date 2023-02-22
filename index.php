<?php

  require 'vendor/autoload.php';

  use App\Category;
  use App\Product;
  use App\SQLiteConnection;


  $conn = new SQLiteConnection();
  // https://www.sqlitetutorial.net/sqlite-php/insert/
  $pdo = $conn->connect();

  $sqliteC = new Category($pdo);
  $sqliteP = new Product($pdo);


  if (!$pdo) {
    die('Whoops, could not connect to the database!');
  }

  $categories = $sqliteC->getCategories();

  $selcategories = $sqliteP->getSelectedCategories();

  $products = $sqliteP->getProducts();

  $mode = isset($_GET['mode']) ? $_GET['mode'] : 'list';

?>

<!DOCTYPE html>
<html lang="ca">
  <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="assets/features.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.1.2/dist/axios.min.js"></script>
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <link id="favicon" rel="shortcut icon" href="assets/logo.png">
  </head>
  <body>

  <main>

  <div class="container px-4 py-3" id="featured-3">
    <h2 id="mode" class="pb-2 border-bottom">
<?php
      if ($mode=='shop') {
?>
        <a href="index.php?mode=list"><img src="assets/compra.png"> Compra Super</a>
<?php
      } else {
?>
        <a href="index.php?mode=shop"><img src="assets/llista.png"> Llista Super</a>

        <label class="float-end">
        <button type="button" id="btn-delete" class="btn btn-lg btn-success"></button>
        <button type="button" id="btn-modal"  class="btn btn-lg btn-primary"
                class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNou"></button>
      </label>
<?php
      }
?>       
    </h2>

<!-- The Modal -->
<div class="modal" id="modalNou">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNouLabel">Nou producte</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" action="insert.php">
          <div class="mb-3">
            <label for="productCategory" class="form-label">Categoria</label>
            <select name="id_category" class="form-select" id="productCategory" required>
              <option value=""></option>>
<?php
              foreach( $categories as $id => $category) {
?>
                <option value="<?php echo $id; ?>"><?php echo $category; ?></option>>
<?php
              }
?>
            </select>
          </div>
          <div class="mb-3">
            <label for="productName" class="form-label">Producte</label>
            <input type="text" name="name" class="form-control" id="producName" aria-describedby="productName" required>
          </div>
          <button type="submit" class="btn btn-primary">Afegir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
    if (isset($_GET['msg'])) {
      if ($_GET['msg']>0) {
?>
        <div id="msg" class="alert alert-success" role="alert">
          Producte afegit correctament
        </div>
<?php
      } else {
?>
        <div id="msg" class="alert alert-danger" role="alert">
          <?php echo $_GET['msg']; ?>
        </div>
<?php
      }
    } else {
?>
      <div id="msg"></div>
<?php      
    }
?>
    <div class="row g-4 py-3 row-cols-1 row-cols-lg-3">
<?php
    foreach( $categories as $id_cat => $category) {

      if (in_array($id_cat, $selcategories) || ($mode=='list')) {
?>
      
        <div class="feature col">
          <div class="feature-title">
            <div class="feature-icon bg-warning bg-gradient">
              <img src="assets/<?php echo strtolower($category); ?>.png">
            </div>
          </div>
          <div>
            <h3><?php echo $category; ?></h3>
          <p>
<?php
            if (isset($products[$id_cat])) {
              foreach( $products[$id_cat] as $product) {
                if (($mode=='list') || ($product['active']!=0)) {
?>
                  <button class="btn btn-sm btn-<?php echo $product['active']; ?>" 
                          id="prod_<?php echo $product['id']; ?>"
                          onclick="change(<?php echo $product['id']; ?>);">
                    <?php echo $product['name']; ?>
                  </button>
<?php 
                }
              }
            }
?>
          </p>
          </div>
        </div>
<?php
      }
    }
?>
     
    </div>
  </div>

  <div class="b-example-divider text-center">
    <small>lluis.manies.cat @ <?php echo date('Y'); ?></small>
    <br><small><i>icons by <a href="https://icons8.com/icons/dotty">icons8.com</a></i></small>
  </div>

  </main>

  <script type="text/javascript">

    const myTimeout = setTimeout(hideMessage, 2000)

    function hideMessage() {
      document.getElementById('msg').style.display = "none"
    }

    if (document.getElementById('btn-delete')) {
      document.getElementById('btn-delete').onclick = function(){
        if (confirm('Vols resetejar la llista de producte seleccionats?')) {
          axios.post('update.php', {
            id:    0,
            actiu: 0
          })
          .then(function (response) {
            document.location =  'index.php?mode=list'
          })
          .catch(function (error) {
            console.log(error)
          })
        }
      };
    }

    function change( id ) {

      let bt = document.getElementById('prod_' + id)
      let activate = 0
      let remove   = 'btn-2'

<?php if ($mode=='list') { ?>
        if (bt.classList.contains('btn-0')) {
          activate = 1
          remove = 'btn-0'
        } else if (bt.classList.contains('btn-1')) {
          remove = 'btn-1'
        }
<?php } else { ?>
        if (bt.classList.contains('btn-1')) {
          remove = 'btn-1'
          activate = 2
        } else {
          activate = 1
        }
<?php } ?>

      axios.post('update.php', {
        id:    id,
        actiu: activate
      })
      .then(function (response) {
        bt.classList.remove( remove )
        bt.classList.add('btn-' + activate )
      })
      .catch(function (error) {
        console.log(error)
      })

    }

  </script>
  
  </body>
</html>
