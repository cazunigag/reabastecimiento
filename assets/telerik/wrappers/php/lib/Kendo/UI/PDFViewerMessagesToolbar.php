<?php

namespace Kendo\UI;

class PDFViewerMessagesToolbar extends \Kendo\SerializableObject {
//>> Properties

    /**
    * ### messages.toolbar.exportAs String  (default: "Export") ### messages.toolbar.download String (default: "Download") ### messages.toolbar.pager Object ### messages.toolbar.pager.first String  (default: "Go to the first page") ### messages.toolbar.pager.previous String (default: "Go to the previous page") ### messages.toolbar.pager.next String (default: "Go to the next page") ### messages.toolbar.pager.last String (default: "Go to the last page") ### messages.toolbar.pager.of String (default: " of {0} ") ### messages.toolbar.pager.page String (default: "page") ### messages.toolbar.pager.pages String (default: "pages") ### messages.errorMessages Object ### messages.errorMessages.notSupported  String (default: "Only pdf files allowed.") ### messages.errorMessages.parseError  String (default: "PDF file fails to process.") ### messages.errorMessages.notFound  String (default: "File is not found.") ### messages.dialogs Object ### messages.dialogs.exportAsDialog Object ### messages.dialogs.exportAsDialog.title String (default: "Export...") ### messages.dialogs.exportAsDialog.defaultFileName String (default: "Document") ### messages.dialogs.exportAsDialog.pdf String (default: "Portable Document Format (.pdf)") ### messages.dialogs.exportAsDialog.png String (default: "Portable Network Graphics (.png)") ### messages.dialogs.exportAsDialog.svg String (default: "Scalable Vector Graphics (.svg)") ### messages.dialogs.exportAsDialog.labels Object ### messages.dialogs.exportAsDialog.labels.fileName String  (default: "File name") ### messages.dialogs.exportAsDialog.labels.saveAsType String  (default: "Save as") ### messages.dialogs.exportAsDialog.labels.page String  (default: "Page") ### messages.dialogs.okText String  (default: "OK") ### messages.dialogs.save String  (default: "Save") ### messages.dialogs.cancel String  (default: "Cancel")
    * @param string $value
    * @return \Kendo\UI\PDFViewerMessagesToolbar
    */
    public function open($value) {
        return $this->setProperty('open', $value);
    }

//<< Properties
}

?>
