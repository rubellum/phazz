import React from 'react';

import {
    Badge, Box,
    Button,
    ChakraProvider,
    Container,
    Heading,
    Link,
    Table,
    TableContainer,
    Tbody,
    Td,
    Th,
    Thead,
    Tr
} from '@chakra-ui/react'
import useSWR from 'swr'
import ErrorAlert from "../components/ErrorAlert";
import Loading from "../components/Loading";

export default function (props) {
    const fetcher = (...args) => fetch(...args).then(res => res.json())
    const {data, error, isLoading} = useSWR(props.path.render, fetcher)

    if (error) return <ErrorAlert/>
    if (isLoading) return <Loading/>

    // const Submit = () => {
    //     let data = {
    //         ...type.formData,
    //         ...{token: props.token},
    //     }
    //     fetch(
    //         props.path.action, {
    //             method: 'POST',
    //             headers: {'Content-Type': 'application/json'},
    //             body: JSON.stringify(data)
    //         })
    //         .then((response) => response.json())
    //         .then((responseJson) => {
    //
    //         })
    // }

    return (
        <ChakraProvider>
            <Container my='1.5rem'>
                <Heading size='lg'><Link href='/'>Phazz</Link></Heading>
            </Container>
            <Container>
                <Heading mb='3' size='md'>Tasks</Heading>
                <TableContainer>
                    <Table size='md'>
                        <Thead>
                            <Tr>
                                <Th>Name</Th>
                                <Th width="120px" textAlign='center'>State</Th>
                                <Th width="120px" textAlign='center'>Action</Th>
                            </Tr>
                        </Thead>
                        <Tbody>
                            {data.items.map((item, index) => {
                                const button = item.showDownloadLink ? (
                                    <Button colorScheme='blue' as='a' href={item.downloadLink}>Download</Button>
                                ) : null
                                return (
                                    <Tr key={index}>
                                        <Td>
                                            {item.name}
                                        </Td>
                                        <Td textAlign='center'>
                                            <Badge colorScheme={item.stateColorScheme}>{item.state}</Badge>
                                        </Td>
                                        <Td textAlign='center'>
                                            {button}
                                            <Button ml='2' as='a' href={item.detailLink}>Detail</Button>
                                        </Td>
                                    </Tr>
                                )
                            })}
                        </Tbody>
                    </Table>
                </TableContainer>
            </Container>
        </ChakraProvider>
    )
}
