<!DOCTYPE html>
<html <?php $this->Document->language() ?> <?php $this->Document->textDirection() ?>>
    <head>
        <?php $this->Document->charset(); ?>
        <?php $this->Document->title(); ?>

        <?php $this->Stylesheets->printStylesheets(); ?>
    </head>
    <body>
