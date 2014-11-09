/**
 * Copyright Isocra Ltd 2004
 * You can use, modify and freely distribute this file as long as you credit Isocra Ltd.
 * There is no explicit or implied guarantee of functionality associated with this file, use it at your own risk.
 */
package fishgenerator.util;

import java.sql.DatabaseMetaData;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.PreparedStatement;
import java.sql.ResultSetMetaData;
import java.util.Properties;
import java.io.FileInputStream;
import java.io.IOException;

/**
 * This class connects to a database and dumps all the tables and contents out
 * to stdout in the form of a set of SQL executable statements
 */
public class Db2Sql {

	/** Dump the whole database to an SQL string */
	public static String dumpDB(String dbName) {

		// Default to not having a quote character
		String columnNameQuote = "`";
		DatabaseMetaData dbMetaData = null;
		Connection dbConn = null;
		try {

			dbConn = DbConnector.getConnection(dbName);
			dbMetaData = dbConn.getMetaData();
		} catch (Exception e) {
			System.err.println("Unable to connect to database: " + e);
			return null;
		}

		try {
			StringBuffer result = new StringBuffer();

			ResultSet rs = dbMetaData.getTables(null, null, "%", null);
			if (!rs.next()) {

				rs.close();
			} else {

				do {
					String tableName = rs.getString("TABLE_NAME");
					String tableType = rs.getString("TABLE_TYPE");
					if ("TABLE".equalsIgnoreCase(tableType)) {
						result.append("\n\n-- " + tableName);
						result.append("\nCREATE TABLE " + tableName + " (\n");
						ResultSet tableMetaData = dbMetaData.getColumns(null,
								null, tableName, "%");
						boolean firstLine = true;
						while (tableMetaData.next()) {
							if (firstLine) {
								firstLine = false;
							} else {
								// If we're not the first line, then finish the
								// previous line with a comma
								result.append(",\n");
							}
							String columnName = tableMetaData
									.getString("COLUMN_NAME");
							String columnType = tableMetaData
									.getString("TYPE_NAME");
							// WARNING: this may give daft answers for some
							// types on some databases (eg JDBC-ODBC link)
							int columnSize = tableMetaData
									.getInt("COLUMN_SIZE");
							String nullable = tableMetaData
									.getString("IS_NULLABLE");
							String nullString = "NULL";
							if ("NO".equalsIgnoreCase(nullable)) {
								nullString = "NOT NULL";
							}
							result.append("    " + columnNameQuote + columnName
									+ columnNameQuote + " " +  " " + nullString);
						}
						tableMetaData.close();

						// Now we need to put the primary key constraint
						try {
							ResultSet primaryKeys = dbMetaData.getPrimaryKeys(
									null, null, tableName);
							// What we might get:
							// TABLE_CAT String => table catalog (may be null)
							// TABLE_SCHEM String => table schema (may be null)
							// TABLE_NAME String => table name
							// COLUMN_NAME String => column name
							// KEY_SEQ short => sequence number within primary
							// key
							// PK_NAME String => primary key name (may be null)
							String primaryKeyName = null;
							StringBuffer primaryKeyColumns = new StringBuffer();
							while (primaryKeys.next()) {
								String thisKeyName = primaryKeys
										.getString("PK_NAME");
								if ((thisKeyName != null && primaryKeyName == null)
										|| (thisKeyName == null && primaryKeyName != null)
										|| (thisKeyName != null && !thisKeyName
												.equals(primaryKeyName))
										|| (primaryKeyName != null && !primaryKeyName
												.equals(thisKeyName))) {
									// the keynames aren't the same, so output
									// all that we have so far (if anything)
									// and start a new primary key entry
									if (primaryKeyColumns.length() > 0) {
										// There's something to output
										result.append(",\n    PRIMARY KEY ");
										if (primaryKeyName != null) {
											result.append(primaryKeyName);
										}
										result.append("("
												+ primaryKeyColumns.toString()
												+ ")");
									}
									// Start again with the new name
									primaryKeyColumns = new StringBuffer();
									primaryKeyName = thisKeyName;
								}
								// Now append the column
								if (primaryKeyColumns.length() > 0) {
									primaryKeyColumns.append(", ");
								}
								primaryKeyColumns.append(primaryKeys
										.getString("COLUMN_NAME"));
							}
							if (primaryKeyColumns.length() > 0) {
								// There's something to output
								result.append(",\n    PRIMARY KEY ");
								if (primaryKeyName != null) {
									result.append(primaryKeyName);
								}
								result.append(" ("
										+ primaryKeyColumns.toString() + ")");
							}
						} catch (SQLException e) {
							// NB you will get this exception with the JDBC-ODBC
							// link because it says
							// [Microsoft][ODBC Driver Manager] Driver does not
							// support this function
							System.err
									.println("Unable to get primary keys for table "
											+ tableName + " because " + e);
						}

						result.append("\n);\n");

						// Right, we have a table, so we can go and dump it
						dumpTable(dbConn, result, tableName);
					}
				} while (rs.next());
				rs.close();
			}
			dbConn.close();
			return result.toString();
		} catch (SQLException e) {
			e.printStackTrace(); // To change body of catch statement use
									// Options | File Templates.
		}
		return null;
	}

	/** dump this particular table to the string buffer */
	private static void dumpTable(Connection dbConn, StringBuffer result,
			String tableName) {
		try {
			// First we output the create table stuff
			PreparedStatement stmt = dbConn.prepareStatement("SELECT * FROM "
					+ tableName);
			ResultSet rs = stmt.executeQuery();
			ResultSetMetaData metaData = rs.getMetaData();
			int columnCount = metaData.getColumnCount();

			// Now we can output the actual data
			result.append("\n\n-- Data for " + tableName + "\n");
			result.append("INSERT INTO " + tableName + " VALUES");
			while (rs.next()) {
				if(!rs.isFirst()) {
					result.append(", ");
				}
				result.append("(");
				for (int i = 0; i < columnCount; i++) {
					if (i > 0) {
						result.append(", ");
					}
					Object value = rs.getObject(i + 1);
					if (value == null) {
						result.append("NULL");
					} else {
						String outputValue = value.toString();
						outputValue = outputValue.replaceAll("'", "\\'");
						result.append("'" + outputValue + "'");
					}
				}
				result.append(")");
			}
			result.append(";\n");
			rs.close();
			stmt.close();
		} catch (SQLException e) {
			System.err.println("Unable to dump table " + tableName
					+ " because: " + e);
		}
	}
}