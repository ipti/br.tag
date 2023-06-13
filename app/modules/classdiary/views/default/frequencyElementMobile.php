<table class="tag-table-secondary">
    <thead>
        <tr>
            <th class="text-align--left">
                Nome
            </th>
            <th class="text-align--right">
                Faltas
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($frequency as $f) {
           echo "<tr>
                    <td><label for=".$f["studentId"].">".$f["studentName"]."</td>
                    <td " . (!$f["schedule"]["available"] ? "disabled" : "") . " ><input id=".$f["studentId"]." type='checkbox' " .(!$f["schedule"]["available"] ? "disabled" : ""). " " . ( $f["schedule"]["fault"] ? "checked" : "") . "
                    studentId='" . $f["studentId"] . "' day='" . $f["schedule"]["day"] . "' schedule='" . $f["schedule"]["schedule"] ."' /></td>
                </tr>";
            };
        ?>
    </tbody>
</table>