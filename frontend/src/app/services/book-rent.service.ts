import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BookRentService {

  constructor(private http: HttpClient) { }

  rentBook(body: any) {
    const url = `${environment.apiUrl}/rent-book`;
    return this.http.post(url, body);
  }

  listRentedBook() {
    const url = `${environment.apiUrl}/get-rented-book`;
    return this.http.get(url);
  }

  getInvoice(invoiceId: number) {
    const url = `${environment.apiUrl}/get-invoice/` + invoiceId;
    window.open(url);
  }

  returnBook(body: any, bookId: string) {
    const url = `${environment.apiUrl}/return-book/` + bookId;
    return this.http.post(url, body);
  }
}
