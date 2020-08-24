import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class BookListService {

  constructor(private http: HttpClient) { }

  getBookList() {
    const url = `${environment.apiUrl}/get-all-books`;
    return this.http.get(url);
  }
}
