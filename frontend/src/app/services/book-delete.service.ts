import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BookDeleteService {

  constructor(private http: HttpClient) { }

  deleteBook(bookId: number, body: any) {
    const url = `${environment.apiUrl}/delete-book/` + bookId;
    return this.http.post(url, body);
  }
}
