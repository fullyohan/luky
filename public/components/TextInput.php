
<?php function TextInput($type,$name,$label,$placeholder,$isRequired,$subtitle=null,$value=''){ ?>
    <?php if ($type === 'price') $type = 'number' ?>
    <div class="mb-3">
        <label 
            for="<?php echo $name ?>" 
            class="form-label fw-semibold text-secondary small"
        >
            <?php echo $label ?>
        </label>
        <input 
            type="<?php echo $type ?>" 
            name="<?php echo $name ?>" 
            id="<?php echo $name ?>" 
            value="<?= $value ?>"
            class="form-control form-control-lg border-2" 
            placeholder="<?php echo $placeholder ?>" 
            <?php echo $isRequired ? 'required' : '' ?> 
        >
        <?php if ($subtitle) echo "<div class='form-text text-muted xsmall'>$subtitle</div>" ?>
    </div>
<?php } ?>

