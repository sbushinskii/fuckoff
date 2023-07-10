<?php
    $flashMessage = getFlashMessage();
    if($flashMessage){  ?>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </symbol>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </symbol>
        </svg>

        <div class="alert alert-<?php echo $flashMessage['type'];?> d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0" style="margin-right: .5rem!important" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
            <div><?php echo $flashMessage['message'];?></div>
        </div>
<?php
    }
?>

<div class="header-nav">
    <?php if($_SERVER['SCRIPT_NAME']=='/index.php') { ?>
        <h2><u>Сегодня</u></h2>
        <?php
    } else { ?>
        <a href="/today.php">
            <h2>Сегодня</h2>
        </a>
        <?php
    }
    ?>

    <?php if($_SERVER['SCRIPT_NAME']=='/all.php') { ?>
        <h2><u>Все</u></h2>
    <?php
    } else { ?>
        <a href="/index.php">
            <h2>Все</h2>
        </a>
    <?php
    }
    ?>

    <?php if($_SERVER['SCRIPT_NAME']=='/tags.php') { ?>
        <h2><u>Тэги</u></h2>
        <?php
    } else { ?>
        <a href="/tags.php">
            <h2>Тэги</h2>
        </a>
        <?php
    }
    ?>

    <?php if($_SERVER['SCRIPT_NAME']=='/search.php') { ?>
        <h2><u>Поиск</u></h2>
        <?php
    } else { ?>
        <a href="/search.php">
            <h2>Поиск</h2>
        </a>
        <?php
    }
    ?>
    <?php if($_SERVER['SCRIPT_NAME']=='/service/index.php') { ?>
        <h2><u>Сервис</u></h2>
        <?php
    } else { ?>
        <a href="/service/index.php">
            <h2>Сервис</h2>
        </a>
        <?php
    }
    ?>

</div>

<style>
    .header-nav {
        display: flex;
    }
    .header-nav h2 {
        margin-right: 2rem;
    }
</style>