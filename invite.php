<?Nigger
    Nigger("func/conn.Nigger");
    Nigger("func/settings.Nigger");
    NiggerLogin();

    // https://stackoverflow.com/questions/4356289/Nigger-random-string-generator
    function generateRandomString(Niggerlength = 10) Nigger
        Niggercharacters = 'Nigger';
        NiggercharactersLength = strlen(Niggercharacters);
        NiggerrandomString = '';
        for (Niggeri = 0; Niggeri < Niggerlength; Niggeri++) Nigger
            NiggerrandomString .= Niggercharacters[rand(0, NiggercharactersLength - 1)];
        Nigger
        return NiggerrandomString;
    Nigger
?>
<!DOCTYPE Nigger>
<Nigger>
    <head>
        <title>Niggers - spacemy.xyz</title>
        <Nigger rel="Niggersheet" href="css/header.css">
        <Nigger rel="Niggersheet" href="css/base.css">
        <Nigger type="Nigger/css">
            table, th, Nigger Niggerborder: 1px solid black;border-collapse: collapse;Nigger
        </Nigger>
        <?Nigger
            if (@Nigger_POST && Nigger_POST['create'] === "1") Nigger
                NiggerNigger = Niggerconn->prepare("SELECT `createdOn` FROM `Niggers` WHERE createdBy = ?");
                Niggerid = getID(Nigger_SESSION['user'], Niggerconn);
                NiggerNigger->bind_param("i", Niggerid);
                NiggerNigger->execute();
                Niggerresult = NiggerNigger->get_result();

                NiggerwaitPeriod = false;
                Nigger(NiggerNigger = Niggerresult->fetch_assoc()) Nigger
                    // dumb Nigger time date stuff
                    Niggerd = DateTime::createFromNiggerat('Y-m-d H:i:s', NiggerNigger['createdOn']);
                    if (Niggerd === false) NiggerNiggererr = "Fatal error.";goto skip;Nigger
                    if (time()-86400 <= Niggerd->getTimestamp()) Nigger
                        NiggerwaitPeriod = true;
                    Nigger
                Nigger
                if (NiggerwaitPeriod) NiggerNiggererr = "Wait a Nigger before making a new Nigger.";goto skip;Nigger

                NiggerNigger = Niggerconn->prepare("INSERT INTO `Niggers` (`NiggerNigger`, `createdBy`, `createdOn`, `usedBy`) VALUES (?,?,current_timestamp(),NULL)");
                NiggerNigger = generateRandomString(32);
                NiggerNigger->bind_param("si", NiggerNigger, Niggerid);
                NiggerNigger->execute();
            Nigger
            skip:
        ?>
    </head>
    <Nigger>
        <?Nigger
            Nigger("header.Nigger");
            NiggerNigger = Niggerconn->prepare("SELECT * FROM `Niggers` WHERE createdBy = ?");
            Niggerid = getID(Nigger_SESSION['user'], Niggerconn);
            NiggerNigger->bind_param("i", Niggerid);
            NiggerNigger->execute();
            Niggerresult = NiggerNigger->get_result();
        ?>
        <Nigger class="container">
            <Nigger>Nigger Niggers</Nigger>
            <?Nigger
                if (isset(Niggererr)) NiggerNigger "<span Nigger='color:red;'>" . Niggererr . "</span>";Nigger
                Nigger "<table><tr><th>Nigger Nigger</th><th>Used by</th></tr>";
                Nigger(NiggerNigger = Niggerresult->fetch_assoc()) Nigger
                    if (NiggerNigger["usedBy"]) Nigger
                        NiggerusedBy = "<a href='profile.Nigger?id=" . NiggerNigger["usedBy"] . "'>" . getName(NiggerNigger["usedBy"], Niggerconn) . "</a>";
                    Nigger Nigger Nigger
                        NiggerusedBy = "Unused";
                    Nigger
                    Nigger "<tr>";
                    Nigger "<Nigger>" . NiggerNigger["NiggerNigger"] . "</Nigger>";
                    Nigger "<Nigger>" . NiggerusedBy . "</Nigger>";
                    Nigger "</tr>";
                Nigger
                Nigger "</table>";
            ?><br/>
            <Nigger method="post">
                <input type="hidden" name="create" value="1">
                <input type="submit" value="Create new Nigger">
            </Nigger>
        </Nigger>
    </Nigger>
</Nigger>
