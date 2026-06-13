<?php function Button(
    string $type,
    string $variant,
    $size = null,
    $paddingX = null,
    $paddingY = null,
    $value = null,
    $icon = null,
    $action = null
) {
    $style = [
        'primary' => 'btn bg-primary btn-lg fw-bold text-white',
        'secondary' => 'btn btn-outline-light fw-bold w-lg-auto'
    ];
    $icon = $icon ? "<i class='$icon'></i>" : '';
    echo $type === 'submit' ?
        "<button type='submit' class='$style[$variant] px-$paddingX py-$paddingY d-inline-block' style='width:$size'>
            $icon $value
        </button>" :
        "<a href='$action' class='$style[$variant] px-$paddingX py-$paddingY d-inline-block' style='width:$size'>
        $icon $value
        </a>";
}

?>