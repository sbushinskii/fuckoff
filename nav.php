<div class="header-nav">
    <?php if($_SERVER['SCRIPT_NAME']=='/today.php') { ?>
        <h2><u>Сегодня</u></h2>
        <?php
    } else { ?>
        <a href="/today.php">
            <h2>Сегодня</h2>
        </a>
        <?php
    }
    ?>

    <?php if($_SERVER['SCRIPT_NAME']=='/index.php') { ?>
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