import {
  Request,
  Response,
  ServerAdapter,
  ServerEndpoint,
  Tokens,
  UsernamePasswordCredentials
} from 'auth-toolbox'

export interface LoginResponse {
  token: string,
}

export default class SymfonyLexikAdapter implements ServerAdapter<UsernamePasswordCredentials> {
  asLoginRequest (loginEndpoint: ServerEndpoint, credentials: UsernamePasswordCredentials): Request {
    const data = credentials
    return { ...loginEndpoint, data }
  }

  asLogoutRequest (logoutEndpoint: ServerEndpoint, tokens: Tokens): Request {
    throw Error('Not implemented')
  }

  asRenewRequest (renewEndpoint: ServerEndpoint, tokens: Tokens): Request {
    throw Error('Not implemented')
  }

  getResponseTokens (response: Response): Tokens {
    const loginResponse: LoginResponse = response.data

    return { access: { value: loginResponse.token}}
  }

  setAccessToken (request: Request, accessToken: string): void {
    if (!request.headers) {
      request.headers = {}
    }

    request.headers.Authorization = 'Bearer ' + accessToken
  }

  accessTokenHasExpired (request: Request, response: Response): boolean {
    return !!request.headers && !!request.headers.Authorization && response.status === 401 && response.data.message === 'Expired JWT Token'
  }

  refreshTokenHasExpired (request: Request, response: Response): boolean {
    return true
  }
}
