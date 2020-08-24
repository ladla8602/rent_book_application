import { Component, Inject, OnInit } from '@angular/core';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';

interface DialogData {
    message: string;
    type: string;
}

@Component({
  selector: 'app-alert-dialog-box',
  templateUrl: './alert-dialog-box.component.html',
  styleUrls: ['./alert-dialog-box.component.scss']
})
export class AlertDialogBoxComponent implements OnInit {

  constructor(public dialogRef: MatDialogRef<AlertDialogBoxComponent>,
              @Inject(MAT_DIALOG_DATA) public data: DialogData) { }

  ngOnInit(): void {
    setTimeout(() => {
      this.dialogRef.close(false);
    }, 2000);
  }

  close() {
    this.dialogRef.close(false);
  }
}
