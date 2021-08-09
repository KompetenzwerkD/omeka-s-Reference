# Omeka S module Reference

This Omeka S module allows to attach biblographic references to items.

## Requirements

Omeka S > 3.0.0

## Usage

### Installation

This module can be installed like any other Omeka S module. Download the files in this repo and place thim within a directory named `Reference` in the Omeka S `modules` directory.

After that, in the admin panel, select `Modules` and install the module.

### Configuration

The 'references' widget will only be displayed for items of resource templates specified in the modules configuration. To add resource templates, click on `Configuration` button in `Admin > Modules`. Add the name of the resource templates in the text field, one name per line.


### Add bibliographic resources

References link an item to bibliographic resource (which itself is an item). To create a bibliographic resource, create an new item and select the `Bibl` resource template (the template was created during the module installation), and fill in the necessary bibliographic information.

The `alternative` label field acts as a short code for the bibliographic resoruce and will be displayed with the title in the reference form. Ideally it should contain the author's name and year. It also acts as an sorting key for the bibl. resources, therefore the suggested format is: `<Last name> <year of publication>`.


### Add references to items

For the item of the resource templates specified in the configuration, the references widget will be displayed under the item's metadata information. By pressing `Add reference` the create reference form will be displayed. Select a resource from the dropdown and provide additional reference information (e.g. page number) and create the reference by pressing the `Add` button.



## Authors

kompetenzwerkd@saw-leipzig.de

## License

GPLv3

## Copyright 

2021, Sächsische Akademie der Wissenschaften zu Leipzig
