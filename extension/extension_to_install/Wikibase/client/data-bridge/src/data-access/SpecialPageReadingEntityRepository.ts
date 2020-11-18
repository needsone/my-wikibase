import JQueryStatic from 'jquery';
import HttpStatus from 'http-status-codes';
import EntityNotFound from '@/data-access/error/EntityNotFound';
import TechnicalProblem from '@/data-access/error/TechnicalProblem';
import JQueryTechnicalError from '@/data-access/error/JQueryTechnicalError';
import ReadingEntityRepository from '@/definitions/data-access/ReadingEntityRepository';
import EntityRevision from '@/datamodel/EntityRevision';
import Entity from '@/datamodel/Entity';
import jqXHR = JQuery.jqXHR;
import StatementMap from '@/datamodel/StatementMap';

interface SpecialPageApiResponse {
	entities: {
		[x: string]: {
			claims: StatementMap;
			lastrevid: number;
		};
	};
}

export default class SpecialPageReadingEntityRepository implements ReadingEntityRepository {
	private readonly $: JQueryStatic;
	private readonly specialEntityDataUrl: string;

	public constructor( $: JQueryStatic, specialEntityDataUrl: string ) {
		this.$ = $;
		this.specialEntityDataUrl = this.trimTrailingSlashes( specialEntityDataUrl );
	}

	public getEntity( entityId: string, _rev?: number ): Promise<EntityRevision> {
		return Promise.resolve( this.$.get( this.buildRequestUrl( entityId ) ) )
			.then( ( data: unknown ): EntityRevision => {
				if ( !this.isWellFormedResponse( data ) ) {
					throw new TechnicalProblem( 'Result not well formed.' );
				}
				if ( !data.entities[ entityId ] ) {
					throw new EntityNotFound( 'Result does not contain relevant entity.' );
				}
				return new EntityRevision(
					new Entity( entityId, data.entities[ entityId ].claims ),
					data.entities[ entityId ].lastrevid,
				);
			}, ( error: jqXHR ): never => {
				if ( error.status && error.status === HttpStatus.NOT_FOUND ) {
					throw new EntityNotFound( 'Entity flagged missing in response.' );
				}

				throw new JQueryTechnicalError( error );
			} );

	}

	private isWellFormedResponse( data: unknown ): data is SpecialPageApiResponse {
		return typeof data === 'object' && data !== null && 'entities' in data;
	}

	private buildRequestUrl( entityId: string ): string {
		return `${this.specialEntityDataUrl}/${entityId}.json`;
	}

	private trimTrailingSlashes( string: string ): string {
		return string.replace( /\/$/, '' );
	}

}
