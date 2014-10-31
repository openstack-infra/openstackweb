<hr>
<h2>Offices</h2>
<p>Drag to reorder offices</p>
<form name="offices_form" id="offices_form">
<table style="border: 1px solid #ccc; border-collapse:collapse;clear:both;width:70%;">
    <thead>
        <tr>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:5%;">Order</th>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:50%;">Address 1</th>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:50%;">Address 2</th>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:50%;">City</th>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:50%;">State</th>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:50%;">Zip</th>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:50%;">Country</th>
            <th style="border: 1px solid #ccc;background:#eaeaea;width:10%;">Add/Remove</th>
        </tr>
    </thead>
    <tbody>
    <tr class="add-additional-office" >
        <td style="border: 1px solid #ccc;background:#eaeaea;width:5%;font-weight:bold;">&nbsp;</td>
        <td style="border: 1px solid #ccc;width:30%;background:#fff;">
            <input type="text" style="width:150px;" id="add-office-address1" name="add-office-address1" value="" class="add-control text autocompleteoff">
        </td>
        <td style="border: 1px solid #ccc;width:30%;background:#fff;">
            <input type="text" style="width:150px;" id="add-office-address2" name="add-office-address2" value="" class="add-control text autocompleteoff">
        </td>
        <td style="border: 1px solid #ccc;width:30%;background:#fff;">
            <input type="text" style="width:150px;" id="add-office-city" name="add-office-city" value="" class="add-control text autocompleteoff">
        </td>
        <td style="border: 1px solid #ccc;width:30%;background:#fff;">
            <input type="text" style="width:150px;" id="add-office-state" name="add-office-state" value="" class="add-control text autocompleteoff">
        </td>
        <td style="border: 1px solid #ccc;width:30%;background:#fff;">
            <input type="text" style="width:50px;" id="add-office-zip-code" name="add-office-zip-code" value="" class="add-control text autocompleteoff">
        </td>
        <td style="border: 1px solid #ccc;width:30%;background:#fff;">
            <div style="display:inline-block;max-width:200px;">
                $getCountriesDDL(add-office-country)
            </div>
        </td>
        <td style="border: 1px solid #ccc;background:#eaeaea;width:10%;color:#cc0000;">
            <a id="add-additional-office" name="add-additional-office" href="#">+&nbsp;Add</a>
        </td>
    </tr>
    </tbody>
</table>
</form>