export class Book {
  id: number;
  userId: number;
  bookName: string;
  bookAuthor: string;
  bookPublishOn: Date;
  bookPrice: number;
  isRented: number;

  static fromRequest(data: any): Book {
    const book = new Book();
    book.id = data.id;
    book.bookName = data.name;
    book.bookAuthor = data.author;
    book.bookPublishOn = data.publish_on;
    book.bookPrice = data.price;
    book.isRented = data.rented;
    return book;
  }
}
