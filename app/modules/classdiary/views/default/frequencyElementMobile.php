<table class="column tag-table-secondary js-table-frequency">
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
                    <td> <span class='js-justification' href=''>".$f["studentName"]."</span></td>
                    <td class='justify-content--end' " . (!$f["schedule"]["available"] ? "disabled" : "") . " ><input class='js-frequency-checkbox' id=".$f["studentId"]." type='checkbox' " .(!$f["schedule"]["available"] ? "disabled" : ""). " " . ( $f["schedule"]["fault"] ? "checked" : "") . "
                    studentId='" . $f["studentId"] . "' classrom_id='".$_GET["classrom_fk"]."' stage_fk='".$_GET["stage_fk"]."' schedule='" . $f["schedule"]["schedule"] ."' /></td>
                </tr>";
            };
        ?>
    </tbody>
</table>