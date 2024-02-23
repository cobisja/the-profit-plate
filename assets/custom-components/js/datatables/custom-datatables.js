import DataTable from "datatables.net-bs5";

const defaultDatatableSelector = "table.make-datatable";
const defaultExportButtons = ["copy", "excel", "csv", "pdf", "print"];
const DEFAULT_BUTTON_STYLING = "bs4;";
const allExportButtons = "all";

export default function boot() {
  $(defaultDatatableSelector).each(function (index, table) {
    let tableInstance = $(table);
    build(
      tableInstance,
      tableInstance.data("default-column"),
      tableInstance.data("default-order"),
      tableInstance.data("exclude-sort-columns"),
      tableInstance.data("paging"),
      tableInstance.data("length-menu"),
      tableInstance.data("scroll-x"),
      tableInstance.data("scroll-y"),
      tableInstance.data("scroll-collapse"),
      tableInstance.data("search-box"),
      tableInstance.data("length-selector"),
      tableInstance.data("export-buttons"),
      tableInstance.data("exclude-export-columns"),
      tableInstance.data("export-title"),
      tableInstance.data("export-subtitle"),
      tableInstance.data("button-styling") || DEFAULT_BUTTON_STYLING,
    );
  });
}

function build(
  table,
  defaultColumn,
  defaultOrder,
  excludeSortColumns,
  paging,
  lengthMenu,
  scrollX,
  scrollY,
  scrollCollapse,
  searchBox,
  lengthSelector,
  exportButtons,
  excludeExportColumns,
  exportTitle,
  exportSubtitle,
  buttonStyling,
) {
  searchBox = undefined !== searchBox ? searchBox : true;
  lengthSelector = undefined !== lengthSelector ? lengthSelector : true;

  let options = {
    order: [
      [
        undefined !== defaultColumn ? defaultColumn : 0,
        undefined !== defaultOrder ? defaultOrder : "asc",
      ],
    ],
    paging: undefined !== paging ? paging : true,
    searching: searchBox,
    lengthChange: lengthSelector,
  };

  if (undefined === exportButtons) {
    exportButtons = [];
  }

  if (0 < exportButtons.length) {
    options.dom = "Blfrtip";

    if (DEFAULT_BUTTON_STYLING === buttonStyling) {
      options.buttons = {
        dom: {
          container: {
            className: "mb-5 text-right",
          },
          button: {
            className: "btn btn-secondary btn-shadow-hover",
          },
        },
      };
    }

    options.buttons.buttons = configExportButtons(
      exportButtons,
      columnIndexesForExporting(
        table,
        undefined !== excludeExportColumns ? excludeExportColumns : [],
      ),
      exportTitle,
      exportSubtitle,
    );
  }

  if (undefined !== excludeSortColumns) {
    options.columnDefs = [
      {
        orderable: false,
        targets: excludeSortColumns,
      },
    ];
  }

  if (undefined !== lengthMenu) {
    options.lengthMenu = lengthMenu;
  }

  if (undefined !== scrollX) {
    options.scrollX = scrollX;
  }

  if (undefined !== scrollY) {
    options.scrollY = scrollY;
  }

  if (undefined !== scrollCollapse) {
    options.scrollCollapse = scrollCollapse;
  }

  options.initComplete = function () {
    table.show();
    table.DataTable().columns.adjust();

    if (DEFAULT_BUTTON_STYLING === buttonStyling) {
      $(".dataTables_filter input, .dataTables_length select").addClass(
        "form-control",
      );
    }
  };

  table.DataTable(options);
}

function configExportButtons(
  exportButtons,
  exportColumnIndexes,
  title,
  subtitle,
) {
  let buttonsToShow;

  if (typeof exportButtons === "string") {
    if (allExportButtons === exportButtons) {
      exportButtons = defaultExportButtons;
    } else {
      exportButtons = [exportButtons];
    }
  }

  buttonsToShow = exportButtons.filter((button) =>
    defaultExportButtons.includes(button),
  );

  return buttonsToShow.map((button) => {
    let buttonConfig = {
      extend: button,
      exportOptions: {
        columns: exportColumnIndexes,
      },
    };

    if (undefined !== title) {
      buttonConfig.title = title;
    }

    if (undefined !== subtitle) {
      buttonConfig.messageTop = subtitle;
    }

    return buttonConfig;
  });
}

function columnIndexesForExporting(table, excludeExportColumns) {
  if (!Array.isArray(excludeExportColumns)) {
    excludeExportColumns = [excludeExportColumns];
  }

  const numberOfColumns = table.find("thead tr th").length;
  const columnIndexes = [...Array(numberOfColumns).keys()].map((x) => x);

  return columnIndexes.filter((x) => !excludeExportColumns.includes(x));
}
