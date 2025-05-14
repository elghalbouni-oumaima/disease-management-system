
  $('#add_diagnosis').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Le bouton qui a ouvert la modale
      var patientId = button.data('patient'); // On récupère l'attribut data-patient

      var modal = $(this);
      modal.find('#modalPatientId').val(patientId); // On met l’ID dans le champ caché
  });


  $('#add_treatment').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Récupère le bouton qui a déclenché la modale
    var diagId = button.data('diagid');  // Récupère data-diagid 
    var patientId = button.data('patient'); // Récupère data-patient
    console.log("diagId:", diagId);
    console.log("patientId:", patientId);
    var modal = $(this); 
    modal.find('#modaldiagId').val(diagId); 
    modal.find('#modalPatientIdTreatment').val(patientId); 
    
    });

    $('#delete_warning').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Récupère le bouton qui a déclenché la modale
      var diagId = button.data('diagid');  // Récupère data-diagid 
      console.log("diagId:", diagId);
      var modal = $(this); // La modale elle-même
      modal.find('#modaldiagid').val(diagId); 
    });

    $('#deleteTreat_warning').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Récupère le bouton qui a déclenché la modale
      var treatId = button.data('treatid');  // Récupère data-diagid 
      var modal = $(this); // La modale elle-même
      modal.find('#modaltreat').val(treatId); 
    });
    
    $('#deletepatient_warning').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget); // Récupère le bouton qui a déclenché la modale
      var Id = button.data('id');  // Récupère data-diagid 
      console.log("id= ",Id);
      var modal = $(this); // La modale elle-même
      modal.find('#modalpatientid').val(Id); 
    });
    
