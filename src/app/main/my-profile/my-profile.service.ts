// ==========================================================>> Core Library
import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';

// ==========================================================>> Third Party Library
import { tap, catchError } from 'rxjs/operators';
import { Observable, ReplaySubject } from 'rxjs';
// ==========================================================>> Custom Library
import { environment as env } from 'environments/environment';

export interface User {
    id: number;
    name: string;
    email?: string;
    phone: string;
    avatar: string;
}

export interface ResponseLogin {
    access_token: string;
    token_type: string;
    expires_in: string;
    user: User;
    role: string;
}

@Injectable({
    providedIn: 'root',
})
export class MyProfileService {
    public url: string = env.API_BASE_URL;
    public httpOptions = {
        headers: new HttpHeaders().set('Content-Type', 'application/json'),
    };

    constructor(private http: HttpClient) {}

    // ==================== Get Profile
    getProfile(): Observable<User> {
        return this.http.get<User>(`${this.url}/profile`, this.httpOptions);
    }

    // ==================== Update Profile
    updateProfile(data: any): Observable<any> {
        return this.http.post<any>(
            `${this.url}/profile`,
            data,
            this.httpOptions
        );
    }

    // =================== Update password
    updatePassword(data: any): Observable<any> {
        return this.http.post<any>(
            `${this.url}/profile/change-password`,
            data,
            this.httpOptions
        );
    }

    // ==================== user service for replay ====================== \\
    private _user: ReplaySubject<User> = new ReplaySubject<User>(1);
    set user(value: User) {
        this._user.next(value);
    }
    get user$(): Observable<User> {
        return this._user.asObservable();
    }

    //==========================================
    private _refresh: ReplaySubject<ResponseLogin> =
        new ReplaySubject<ResponseLogin>(1);
    set token(value: ResponseLogin) {
        this._refresh.next(value);
    }
    get token$(): Observable<ResponseLogin> {
        return this._refresh.asObservable();
    }
}
