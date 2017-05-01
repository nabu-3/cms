$(document).ready(function() {
    $('.modal')
        .on('shown.bs.modal', function(e) {
            var vi_editor = $(e.target).find('[data-toggle="visual-editor"]');
            if (vi_editor.length > 0) {
                if (!mxClient.isBrowserSupported()) {
                    // Displays an error message if the browser is not supported.
                    mxUtils.error('Browser is not supported!', 200, false);
                } else {
                    var config = mxUtils.load('/js/visual-editor/config/site-target.xml').getDocumentElement();
                    var editor = new mxEditor(config);
                    editor.setGraphContainer(vi_editor[0]);

                    var data = vi_editor.data();

                    if (data.source) {
                        nabu.loadLibrary('Ajax', function() {
                            var ajax = new Nabu.Ajax.Connector(data.source, 'GET');
                            ajax.addEventListener(new Nabu.Event({
                                onLoad: function(e) {
                                    console.log(e.params.text);
                                }
                            }));
                            ajax.execute();
                        });
                    } else {
                        var graph = editor.graph;
                        var model = graph.getModel();

                        // Gets the default parent for inserting new cells. This
                        // is normally the first child of the root (ie. layer 0).
                        var parent = graph.getDefaultParent();

                        // Adds cells to the model in a single step
                        model.beginUpdate();
                        try
                        {
                          var v1 = graph.insertVertex(parent, null, 'Hello,', 20, 20, 80, 30);
                          var v2 = graph.insertVertex(parent, null, 'World!', 200, 150, 80, 30);
                          var e1 = graph.insertEdge(parent, null, '', v1, v2);
                        }
                        finally
                        {
                          // Updates the display
                          model.endUpdate();
                        }
                    }
                }
            }
        })
        .on('hide.bs.modal', function(e) {
            var vi_editor = $(e.target).find('[data-toggle="visual-editor"]');
            if (vi_editor.length > 0) {
                console.log(e);
            }
        })
    ;
});
