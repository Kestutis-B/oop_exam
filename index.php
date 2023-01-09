<?php
declare(strict_types=1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './vendor/autoload.php';

use Kestutisbilotas\Container\DIContainer;
use Kestutisbilotas\Framework\Router;


//patikrinti, kodel nemato cia DIContainer klases
$container = new DIContainer();
$router = $container->get(Router::class);
$request = (isset($_POST['_method'])) ? strtoupper($_POST['_method']) : $_SERVER['REQUEST_METHOD'];
$router->process($request);


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8"/>
    <title>Elektros apskaitos sistema</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
</head>

<body>

<h1>Mokesčiai už elektrą</h1>

<fieldset>

    <legend>Įveskite savo duomenis</legend>

    <form method="POST" action="./index.php">
        <input type="hidden" name="id" value="">
        <input class="user_input" type="text" name='data' placeholder="Čia įveskite duomenis">
        <input type="submit" value="Įvesti duomenis">
        <p>Nurodykite savo duomenis:</p>
        <p>Per mėnesį suvartotų elektros kilovatvalandžių kiekį, tarifą ir dieninį ar naktinį tarifą, mėnesį
            už kurį yra
            mokama. Darykite tai kaip pavyzdyje žemiau:</p>
        <p class="pvz">300 0.20 diena 12</p>
        <p>arba</p>
        <p class="pvz">300 0.28 naktis 12</p>
    </form>

</fieldset>

<?php if (isset($data)): ?>

    <fieldset>

        <legend>Jūsų įvesti duomenys</legend>

        <div class="red_text">
            <?php if (isset($exception)) echo $exception->getMessage() ?>
        </div>

        <?php if (sizeof($data) > 0): ?>
            <table>
                <thead>
                <tr>
                    <td>Sunaudotas kiekis</td>
                    <td>Tarifas</td>
                    <td>Diena arba naktis</td>
                    <td>Mėnuo</td>
                    <td>Pašalinti duomenis</td>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($data as $key => $value): ?>
                    <tr>
                        <td><?= $value['amount'] ?></td>
                        <td><?= $value['price'] ?></td>
                        <td><?= $value['period'] ?></td>
                        <td><?= $value['month'] ?></td>
                        <td>
                            <form method="POST" action="./index.php">
                                <input type="hidden" name="delete" value="<?php echo $key ?>">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="submit" value="Pašalinti šiuos duomenis">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>

            <div class="below_buttons">
                <?php if (isset($data) && sizeof($data) > 0): ?>
                    <form class="below" method="POST" action="./index.php">
                        <input type="hidden" name="_method" value="COUNT">
                        <input type="submit" value="Skaičiuoti kainą">
                    </form>
                <?php endif; ?>
                <?php if (isset($sums)): ?>
                    <form class="below" method="POST" action="./index.php">
                        <input type="hidden" name="_method" value="PAY">
                        <input type="submit" value="Deklaruoti ir sumokėti">
                    </form>
                <?php endif; ?>
            </div>

            <table>
                <thead>
                <tr>
                    <td>Kaina už dienos metu sunaudotą elektrą</td>
                    <td>Kaina už nakties metu sunaudotą elektrą</td>
                    <td>Kaina iš viso</td>
                </tr>
                </thead>

                <tbody>
                <?php if (isset($sums)): ?>
                    <tr>
                        <td><?= $sums['diena'] ?></td>
                        <td><?= $sums['naktis'] ?></td>
                        <td><?= $sums['suma'] ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </fieldset>

<?php endif; ?>

<?php if (isset($pay) && $pay > 0): ?>
    <h2>Sumokėta už elektrą <?= $pay ?></h2>
<?php endif; ?>

</body>

</html>
