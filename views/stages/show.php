<?php
require_once '../../classes/Stage.php';

require_once '../../classes/Festival.php';

require_once '../../classes/Gump.php';

try {
    $validator = new GUMP();

    $_GET = $validator->sanitize($_GET);

    $validation_rules = [
        'id' => 'required|integer|min_numeric,1',
    ];
    $filter_rules = [
        'id' => 'trim|sanitize_numbers',
    ];

    $validator->validation_rules($validation_rules);
    $validator->filter_rules($filter_rules);

    $validated_data = $validator->run($_GET);

    if (false === $validated_data) {
        $errors = $validator->get_errors_array();

        throw new Exception('Invalid stage id: '.$errors['id']);
    }

    $id = $validated_data['id'];
    $stage = Stage::find($id);

    $img_src = $stage->image_path;

    // if(!strpos($img_src, 'placeimg')) {
    //   $img_src = "../../" . $stage->image_path;
    // }
} catch (Exception $ex) {
    exit($ex->getMessage());
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php require '../../utils/styles.php'; ?>
        <?php require '../../utils/scripts.php'; ?>
    </head>
    <body>
      <?php require '../../utils/toolbar.php'; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php require '../../utils/header.php'; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h2>Stage details</h2>
                    <table id="table-stage" class="table table-hover">
                        <tbody>
                            <tr>
                                <td><img src="<?php echo $img_src; ?>" height="200px" /></td>
                            </tr>
                            <tr>
                                <td width="20%">Title</td>
                                <td><?php echo $stage->title; ?></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><?php echo $stage->description; ?></td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td><?php echo $stage->location; ?></td>
                            </tr>
                            <tr>
                                <td>Festival</td>
                                <td><a href="../festivals/show.php?id=<?php echo $stage->festival_id; ?>" class="btn-link"><?php echo Festival::find($stage->festival_id)->title; ?></a></td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                        <a href="index.php" class="btn btn-default">Cancel</a>
                        <a href="edit.php?id=<?php echo $stage->id; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $stage->id; ?>" class="btn btn-danger">Delete</a>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php require '../../utils/footer.php'; ?>
                </div>
            </div>
        </div>
    </body>
</html>
