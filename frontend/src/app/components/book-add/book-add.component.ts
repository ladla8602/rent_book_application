import { BookAddService } from './../../services/book-add.service';
import { FormGroup, FormBuilder, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { AlertDialogBoxComponent } from '../alert-dialog-box/alert-dialog-box.component';
@Component({
  selector: 'app-book-add',
  templateUrl: './book-add.component.html',
  styleUrls: ['./book-add.component.scss']
})
export class BookAddComponent implements OnInit {
  public newBookForm: FormGroup;
  public loading = false;

  constructor(private formBuilder: FormBuilder,
              private addBookService: BookAddService,
              private dialog: MatDialog) { }

  openAlertDialog(message: string, type: string) {
    const dialogRef = this.dialog.open(AlertDialogBoxComponent, {
      width: '600px',
      data: { message, type },
      position: {
        top: '196px'
      }
    });
    dialogRef.afterClosed().subscribe(result => {

    });
  }

  ngOnInit(): void {
    this.setFormFields();
  }

  setFormFields() {
    this.newBookForm = this.formBuilder.group({
      book_name: ['', Validators.required],
      book_author: ['', Validators.required],
      book_price: ['', Validators.required]
    });
  }

  get f() { return this.newBookForm.controls; }

  onSubmit() {
    this.loading = true;
    // stop here if form is invalid
    if (this.newBookForm.invalid) {
      return;
    }

    const formData = new FormData();
    formData.append('book_name', this.newBookForm.get('book_name').value);
    formData.append('book_author', this.newBookForm.get('book_author').value);
    formData.append('book_price', this.newBookForm.get('book_price').value);

    this.addBookService.addNewBook(formData).subscribe(
      (response) => {
        this.loading = false;
        this.newBookForm.reset();
        this.f.book_name.setErrors(null);
        this.f.book_author.setErrors(null);
        this.f.book_price.setErrors(null);
        this.openAlertDialog('Book Added Successfully', 'success');
      },
      (error) => {
        this.loading = false;
        console.log(error);
        this.openAlertDialog(error, 'failed');
      }
    );
    this.setFormFields();
  }
}
