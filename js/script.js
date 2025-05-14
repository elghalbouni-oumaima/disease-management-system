const table_headings=document.querySelectorAll('thead th'),
table_rows=document.querySelectorAll('tbody tr'),
search= document.querySelector('.input-grp input');
if(search){
    search.addEventListener('input',searchtable);
}
const btn=document.querySelectorAll('#button'),
action=document.getElementById('action');
// 1.search data in the table
function searchtable() {
    const search_data = search.value.toLowerCase();

    table_rows.forEach((row, i) => {
        const table_data = row.textContent.toLowerCase();
        const match = table_data.includes(search_data);

        row.style.setProperty('--delay', i / 25 + 's');

        if (match) {
            row.style.display = "";
            row.classList.remove("hide");

            // Highlight (mettre en jaune)
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => {
                const originalText = cell.textContent;
                const lowerText = originalText.toLowerCase();

                if (search_data !== "" && lowerText.includes(search_data)) {
                    const regex = new RegExp(`(${search_data})`, 'gi');
                    cell.innerHTML = originalText.replace(regex, '<mark>$1</mark>');
                } else {
                    // Si pas de recherche, on remet le texte normal
                    cell.innerHTML = originalText;
                }
            });

        } else {
            row.classList.add("hide");
            row.addEventListener("transitionend", () => {
                row.style.display = "none";
            }, { once: true });
        }
    });

    // Si l'input est vide, enlever tous les <mark>
    if (search_data === "") {
        table_rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach(cell => {
                cell.innerHTML = cell.textContent; // Reset propre
            });
        });
    }
}


//2. sort table
table_headings.forEach((head,i)=>{
    let sort_asc=true;
    head.onclick=()=>{
        table_headings.forEach(head=> head.classList.remove('active'));
        head.classList.add('active');
        document.querySelectorAll('td').forEach(td=> td.classList.remove('active'));
        table_rows.forEach(row=>{
            row.querySelectorAll('td')[i].classList.add('active');
        })
        head.classList.toggle('asc',sort_asc);
        sort_asc=head.classList.contains('asc')? false : true;

        sortTable(i,sort_asc);
    }
})
function sortTable(column, sort_asc) {
    [...table_rows].sort((a, b) => {
        let first_row = a.querySelectorAll('td')[column].textContent.toLowerCase();
        let second_row = b.querySelectorAll('td')[column].textContent.toLowerCase();

        if (first_row < second_row) return sort_asc ? -1 : 1;
        if (first_row > second_row) return sort_asc ? 1 : -1;
        return 0;
    })
    .forEach(sorted_row => document.querySelector('tbody').appendChild(sorted_row));
}


// 3. Converting HTML table to PDF
const pdf_btn=document.querySelector('#pdfbtn');
const treatments_table=document.querySelector('#Treatments_table'),
pdf_table=document.querySelector('#pdftable'),
table=document.querySelector('#Treatments_table div table');
thead_trspan=document.querySelectorAll('#icon_arrow th span'),
thead_tr=document.querySelectorAll('#icon_arrow th');


/*const toPDF=function(treatments_table){
    table.style.borderCollapse ="collapse";
    document.querySelectorAll('td').forEach(td=>{
        td.style.padding="15px";
    });
    table.style.padding="20px";
    thead_trspan.forEach(thspan=>{
        thspan.style.display="none";
     })
    thead_trspan.forEach(thspan=>{
       thspan.style.display="none";
    })
    action.style.display="none";
    btn.forEach(td=>{
        td.style.display="none";
    })
    
    const html_code=`
  
    <script>
            window.onload = function() {
                window.print();
                setTimeout(() => window.close(), 100);
            }
    </script>
    `
    // Ouvre une nouvelle fenêtre et injecte le contenu
    const new_window = window.open('', '_blank');
    new_window.document.open();
    new_window.document.write(html_code);
    new_window.document.close();

    // Réafficher les boutons dans la fenêtre principale
    btn.forEach(td => {
        td.style.display = "flex";
    });  
    action.style.display="";
    thead_trspan.forEach(thspan=>{
        thspan.style.display="";
     })
     document.querySelectorAll('td').forEach(td=>{
        td.style.padding="";
    });
}
*/function generatePDF(x,file_path,y){
    console.log(y);
    /*toPDF(treatments_table) */
    const url = `${file_path}?${y}=${encodeURIComponent(x)}`;
    const new_window = window.open(url, "_blank");
    if (!new_window) {
        alert("Popup blocked. Please allow popups for this site.");
    }
}

// 3. Converting HTML table to JSON
const json_btn=document.querySelector("#toJSON");
const toJSON = function(table){
    const t_head = [];

    // Step 1: Build the table headers array
    document.querySelectorAll("thead th").forEach(th => {
        const headerText = th.textContent.trim().toLowerCase(); 
        t_head.push(headerText.substr(0,headerText.length - 1));
    });

    // Step 2: Process table rows
    const row_data = [];

    document.querySelectorAll("tbody tr").forEach(tr => {
        const t_cellls = tr.querySelectorAll("td");
        const row_obj = {};

        t_cellls.forEach((t_celll, cell_index) => {
            const header = t_head[cell_index];

            // Skip if no header (e.g., action buttons)
            if (header && !header.includes("edit") && !header.includes("action") && header !== "undefined") {
                // Hide all buttons inside the cell
                const t_cellbtns = t_celll.querySelectorAll("button, a"); // buttons or links
                t_cellbtns.forEach(btn => {
                    btn.style.display = "none";
                });

                row_obj[header] = t_celll.textContent.trim();
            }
        });

        // Add this row to the full data array
        row_data.push(row_obj);
    });

    // Step 3: Output the final cleaned JSON
    return JSON.stringify(row_data, null, 4);

}
json_btn.onclick = () =>{
    const json=toJSON(treatments_table);
    downloadFile(json,'json');
}






const excel_btn = document.querySelector('#toEXCEL');

const toExcel = function (table) {
    const thead_row = table.querySelector('thead tr');
    const tbody_rows = table.querySelectorAll('tbody tr');

    let excelContent = '';

    
    // Ajouter les entêtes
    if (thead_row) {
        const headers = [...thead_row.querySelectorAll('th')].map(th => th.childNodes[0].textContent.trim()).filter(header => header !== 'Actions').join('\t');
        excelContent += headers + '\n';
    }

    // Ajouter les lignes du body
    excelContent += [...tbody_rows].map(row => {
        const cells = [...row.querySelectorAll('td')];

        // Enlever le dernier <td> qui est le bouton Actions
        const filteredCells = cells.slice(0, -1);

        return filteredCells.map(cell => cell.textContent.trim()).join('\t');
    }).join('\n');

    return excelContent;
};




excel_btn.onclick = () =>{
    const excel=toExcel(treatments_table);
    downloadFile(excel,'excel');
}

const downloadFile = function(data,filetype,filename=''){
    const a = document.createElement('a');
    a.download=filename;
    const mime_types = {
        "json" : "application/json",
        "csv" : "text/csv",
        "excel" : "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
    }
    a.href = `
       data:${mime_types[filetype]};charset=utf-8,${data});
    `;
    document.body.appendChild(a);
    a.click();
    a.remove();
}

