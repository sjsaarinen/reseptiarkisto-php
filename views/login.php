<div class="container">
    <br>
    <form class="form-inline" role="form" action="kirjautuminen.php" method="POST">
        <div class="form-group">
            <label class="sr-only" for="inputKayttajatunnus">Käyttäjätunnus</label>
            <input type="text" class="form-control" id="inputKayttajatunnus" name="username" placeholder="Käyttäjätunnus" value="<?php if (!empty($data->kayttaja)) echo $data->kayttaja; ?>">
        </div>
        <div class="form-group">
            <label class="sr-only" for="inputSalasana">Salasana</label>
            <input type="password" class="form-control" id="inputSalasana" name="password" placeholder="Salasana">
        </div>
        <button type="submit" class="btn btn-default">Kirjaudu</button>
    </form>
<div>