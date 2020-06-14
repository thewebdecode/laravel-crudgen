<table class="table table-bordered table-striped table-hoverable my-3">
    <thead class="bg-dark text-light">
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Data Type</th>
            <th>Default</th>
            <th>Null</th>
            <th>Unsigned</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="tbody">
        <tr>
            <td>1</td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" name="column_name[]" id="column_name" required>
                </div>
            </td> 
            <td>
                <div class="form-group m-0 p-0">
                    <select class="form-control" name="column_type[]" id="column_type" required>
                        <option value="integer">Int</option>
                        <option value="string">Varchar</option>
                        <option value="text">Text</option>
                        <option value="longText">Longtext</option>
                        <option value="json">Json</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group">
                    <input type="text" class="form-control" name="column_default[]" id="column_default">
                </div>
            </td>
            <td>
                <select class="form-control" name="column_null[]" id="column_null" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </td>
            <td>
                <select class="form-control" name="column_unsigned[]" id="column_unsigned" required>
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
            </td>
            <td>
                <button type="button" class="btn btn-info rounded-circle addMoreCols" id="addMoreCols"><i class="fas fa-plus"></i></button>
            </td>
        </tr>
    </tbody>
</table>

<div>
    <b class="text-danger">Note:</b> 
    <ol type="i">
        <li>
            <code class="badge badge-light text-danger">id</code> and <code class="badge badge-light text-danger">timestamps</code> will be created automaticaly.
        </li>
        <li>
            Use lowercase for the <code class="badge badge-light text-danger">Column names</code>.
        </li>
    </ol>
</div>


